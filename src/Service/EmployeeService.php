<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerEmailUnique;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerPasswordMatches;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\Context\CachedSalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EmployeeService
{
    public function __construct(
        private readonly Connection $connection,
        private readonly CustomerService $customerService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly DataValidator $validator,
        private readonly EntityRepository $myfavOrgEmployeeRepository,
        private readonly CachedSalesChannelContextFactory $contextFactory,
        private readonly SalesChannelContextPersister $contextPersister,
        private readonly SystemConfigService $systemConfigService,)
    {
    }

    /**
     * createEmployeeFromRequest
     *
     * @param  Context $context
     * @param  Request $request
     */
    public function createEmployeeFromRequest(Context $context, DataBag $dataBag, SalesChannelContext $salesChannelContext): mixed
    {
        $hasErrors = false;
        $errors = [];
        $employeeId = Uuid::randomHex();
        $dataBag->set('active', $dataBag->get('active') == 1 ? true : false);
        $dataBag->add(['id' => $employeeId]);
        $dataBag->add(['customerId' => $salesChannelContext->getCustomer()->getId()]);

        try {
            $this->validateFullFields($dataBag, $salesChannelContext);
            $this->myfavOrgEmployeeRepository->create([
                $dataBag->all()
            ], $context);
        } catch(\Exception $e) {
            $violations = $e->getViolations();
            $hasErrors = true;
            $errors = $violations;
        }

        return [
            'id' => $employeeId,
            'hasErrors' => $hasErrors,
            'errors' => $errors
        ];
    }

    /**
     * deleteEmployee
     *
     * @param  Context $context
     * @param  string $aclRoleId
     * @return void
     */
    public function deleteEmployee(Context $context, string $employeeId): void
    {
        $this->myfavOrgEmployeeRepository->delete([
            [
                'id' => $employeeId
            ]
        ], $context);
    }

    /**
     * loadById
     *
     * @param  Context $context
     * @param  string $employeeId
     * @return mixed
     */
    public function loadById(Context $context, string $employeeId): mixed
    {
        $criteria = new Criteria([$employeeId]);
        $this->addDefaultAssociations($criteria);
        $employee = $this->myfavOrgEmployeeRepository->search($criteria, $context)->first();
        return $employee;
    }

    /**
     * loadByEmail
     *
     * @param  Context $context
     * @param  string $email
     * @return mixed
     */
    public function loadByEmail(Context $context, string $email): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('email', $email));
        $this->addDefaultAssociations($criteria);
        $employee = $this->myfavOrgEmployeeRepository->search($criteria, $context)->first();
        return $employee;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @return EntitySearchResult
     */
    public function loadList(Context $context): EntitySearchResult
    {
        $employees = $this->myfavOrgEmployeeRepository->search(new Criteria(), $context);
        return $employees;
    }

    /**
     * updateEmployeeFromRequest
     *
     * @param  Context $context
     * @param  string $employeeId
     * @param  Request $request
     * @param  SalesChannelContext $salesChannelContext
     */
    public function updateEmployeeFromRequest(Context $context, string $employeeId, DataBag $dataBag, SalesChannelContext $salesChannelContext): mixed
    {
        $hasErrors = false;
        $errors = [];
        $dataBag->set('active', $dataBag->get('active') == 1 ? true : false);
        $password = $dataBag->get('password');
        $dataBag->remove('password');
        $dataBag->add(['id' => $employeeId]);

        try {
            $this->validateUserFields($dataBag, $salesChannelContext);
            $this->myfavOrgEmployeeRepository->update([
                $dataBag->all()
            ], $context);
        } catch(\Exception $e) {
            $violations = $e->getViolations();
            $hasErrors = true;
            $errors = $violations;
        }

        // Update password, if it is set.
        if(!$hasErrors) {
            try {
                $passwordDataBag = new DataBag();
                $passwordDataBag->add([
                    'id' => $employeeId,
                    'password' => $password
                ]);

                $this->validatePasswordFields($passwordDataBag, $salesChannelContext);
                $this->myfavOrgEmployeeRepository->update([$passwordDataBag->all()], $context);
            } catch(\Exception $e) {
                $passwordIsBlank = false;
                $violations = $e->getViolations();

                foreach($violations as $violation) {
                    if($violation->getCode() === 'VIOLATION::IS_BLANK_ERROR') {
                        $passwordIsBlank = true;
                    }
                }

                if(!$passwordIsBlank) {
                    $hasErrors = true;
                    $errors = $violations;
                }
            }
        }

        return [
            'hasErrors' => $hasErrors,
            'errors' => $errors
        ];
    }
    
    /**
     * addDefaultAssociations
     *
     * @param  Criteraia $criteria
     * @return void
     */
    private function addDefaultAssociations(Criteria $criteria): void
    {
        $criteria->addAssociation('salutation');
        $criteria->addAssociation('myfavOrgAclRole');
        $criteria->addAssociation('myfavOrgAclRole.myfavOrgAclRoleAttributes');
        $criteria->addAssociation('myfavOrgAclRole.myfavOrgAclRoleAttributes.myfavOrgAclAttribute');
        $criteria->addAssociation('myfavOrgEmployeeAclAttributes');
        $criteria->addAssociation('myfavOrgEmployeeAclAttributes.myfavOrgAclAttribute');
    }

    /**
     * buildViolation
     *
     * @return void
     */
    private function buildViolation(
        string $messageTemplate,
        array $parameters,
        ?string $propertyPath = null,
        ?array $invalidValue = null,
        ?string $code = null
    ): ConstraintViolationInterface {
        return new ConstraintViolation(
            str_replace(array_keys($parameters), array_values($parameters), $messageTemplate),
            $messageTemplate,
            $parameters,
            null,
            $propertyPath,
            $invalidValue,
            null,
            $code
        );
    }

    /**
     * validateFullFields
     *
     * @param  mixed $data
     * @param  mixed $context
     * @return void
     * @throws ConstraintViolationException
     */
    private function validateFullFields(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $definition = new DataValidationDefinition('myfav.employee.create.validation');

        // Add E-Mail checks.
        $definition->add('email', new NotBlank(), new Email(null, 'VIOLATION::INVALID_EMAIL_FORMAT_ERROR'));

        // Add Firstname checks.
        $definition->add('firstName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_FIRST_NAME]));

        // Add Lastname checks.
        $definition->add('lastName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_LAST_NAME]));

        // Add password validation.
        $minPasswordLength = $this->systemConfigService->get('core.loginRegistration.passwordMinLength', $salesChannelContext->getSalesChannel()->getId());
        $definition->add('password', new NotBlank(), new Length(['min' => $minPasswordLength]));
        
        $this->validator->validate($data->all(), $definition);
        $this->emailUniqueValidation($data, $salesChannelContext);
        $this->emailEmployeeUniqueValidation($data, $salesChannelContext);
    }

    /**
     * validatePasswordFields
     *
     * @param  mixed $data
     * @param  mixed $context
     * @return void
     * @throws ConstraintViolationException
     */
    private function validatePasswordFields(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $definition = new DataValidationDefinition('customer.password.update');
        $minPasswordLength = $this->systemConfigService->get('core.loginRegistration.passwordMinLength', $salesChannelContext->getSalesChannel()->getId());
        $definition->add('password', new NotBlank(), new Length(['min' => $minPasswordLength]));
        $this->validator->validate($data->all(), $definition);
    }

    /**
     * validateFullFields
     *
     * @param  mixed $data
     * @param  mixed $context
     * @return void
     * @throws ConstraintViolationException
     */
    private function validateUserFields(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $definition = new DataValidationDefinition('myfav.employee.create.validation');

        // Add E-Mail checks.
        $definition->add('email', new NotBlank(), new Email(null, 'VIOLATION::INVALID_EMAIL_FORMAT_ERROR'));

        // Add Firstname checks.
        $definition->add('firstName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_FIRST_NAME]));

        // Add Lastname checks.
        $definition->add('lastName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_LAST_NAME]));
        
        $this->validator->validate($data->all(), $definition);
        $this->emailUniqueValidation($data, $salesChannelContext);
        $this->emailEmployeeUniqueValidationExceptEditedEmployee($data, $salesChannelContext);
    }

    /**
     * emailUniqueValidation
     *
     * @param  DataBag $data
     * @param  SalesChannelContext $salesChannelContext
     * @return void
     */
    private function emailUniqueValidation(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $value = $data->get('email');
        $query = $this->connection->createQueryBuilder();

        /** @var array{email: string, guest: int, bound_sales_channel_id: string|null}[] $results */
        $results = $query
            ->select('email', 'guest', 'LOWER(HEX(bound_sales_channel_id)) as bound_sales_channel_id')
            ->from('customer')
            ->where($query->expr()->eq('email', $query->createPositionalParameter($value)))
            ->executeQuery()
            ->fetchAllAssociative();

        $results = \array_filter($results, static function (array $entry) use ($salesChannelContext) {
            // Filter out guest entries
            if ($entry['guest']) {
                return false;
            }

            if ($entry['bound_sales_channel_id'] === null) {
                return true;
            }

            if ($entry['bound_sales_channel_id'] !== $salesChannelContext->getSalesChannelId()) {
                return false;
            }

            return true;
        });

        // If we don't have anything, skip
        if ($results !== []) {
            $violation = $this->buildViolation("MYFAV_ORG::EMAIL_ADDRESS_DUPLICATE_IN_ACCOUNT", [], '/email');
            $constraintViolations = new ConstraintViolationList();
            $constraintViolations->add($violation);
            throw new ConstraintViolationException($constraintViolations, $data->all());
        }
    }

    /**
     * emailEmployeeUniqueValidation
     *
     * @param  DataBag $data
     * @param  SalesChannelContext $salesChannelContext
     * @return void
     */
    private function emailEmployeeUniqueValidation(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $value = $data->get('email');
        $query = $this->connection->createQueryBuilder();

        /** @var array{email: string, guest: int, bound_sales_channel_id: string|null}[] $results */
        $results = $query
            ->select('email')
            ->from('myfav_org_employee')
            ->where($query->expr()->eq('email', $query->createPositionalParameter($value)))
            ->executeQuery()
            ->fetchAllAssociative();

        // If we don't have anything, skip
        if ($results !== []) {
            $violation = $this->buildViolation("MYFAV_ORG::EMAIL_ADDRESS_DUPLICATE_IN_EMPLOYEE", [], '/email');
            $constraintViolations = new ConstraintViolationList();
            $constraintViolations->add($violation);
            throw new ConstraintViolationException($constraintViolations, $data->all());
        }
    }

    /**
     * emailEmployeeUniqueValidationExceptEditedEmployee
     *
     * @param  DataBag $data
     * @param  SalesChannelContext $salesChannelContext
     * @return void
     */
    private function emailEmployeeUniqueValidationExceptEditedEmployee(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $employeeId = $data->get('id');
        $value = $data->get('email');
        $query = $this->connection->createQueryBuilder();

        /** @var array{email: string, guest: int, bound_sales_channel_id: string|null}[] $results */
        $results = $query
            ->select('LOWER(HEX(id)) as id', 'email')
            ->from('myfav_org_employee')
            ->where($query->expr()->eq('email', $query->createPositionalParameter($value)))
            ->executeQuery()
            ->fetchAllAssociative();

        // If this is the same mail address, but the employeeId matches too - ignore this one.
        if(count($results) == 1) {
            if(strtolower($results[0]['id']) == strtolower($employeeId)) {
                return;
            }
        }

        // If we don't have anything, skip
        if ($results !== []) {
            $violation = $this->buildViolation("MYFAV_ORG::EMAIL_ADDRESS_DUPLICATE_IN_EMPLOYEE", [], '/email');
            $constraintViolations = new ConstraintViolationList();
            $constraintViolations->add($violation);
            throw new ConstraintViolationException($constraintViolations, $data->all());
        }
    }
}