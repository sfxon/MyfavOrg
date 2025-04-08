<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Doctrine\DBAL\Connection;
use Myfav\Org\Service\MyfavSalesChannelContextService;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerEmailUnique;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerPasswordMatches;
use Shopware\Core\Checkout\Customer\SalesChannel\AbstractRegisterRoute;
use Shopware\Core\Checkout\Customer\Service\EmailIdnConverter;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\NumberRange\ValueGenerator\NumberRangeValueGeneratorInterface;
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


class CustomerService
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EntityRepository $customerRepository,
        private readonly MyfavSalesChannelContextService $myfavSalesChannelContextService,
        private readonly NumberRangeValueGeneratorInterface $numberRangeValueGenerator,
        private readonly DataValidator $validator,
        private readonly SystemConfigService $systemConfigService,)
    {
    }

    /**
     * createCustomerFromRequest
     *
     * @param  Context $context
     * @param  Request $request
     */
    public function createCustomerFromRequest(Context $context, DataBag $dataBag, SalesChannelContext $salesChannelContext): mixed
    {
        $hasErrors = false;
        $errors = [];
        $customerId = Uuid::randomHex();
        EmailIdnConverter::encodeDataBag($dataBag);
        $dataBag->set('customerId', Uuid::randomHex());
        $dataBag->set('active', $dataBag->get('active') == 1 ? true : false);
        $dataBag->add(['id' => $customerId]);
        $dataBag->add(['customerId' => $salesChannelContext->getCustomer()->getId()]);
        $dataBag->add(['customerNumber' => $this->numberRangeValueGenerator->getValue(
            $this->customerRepository->getDefinition()->getEntityName(),
            $context,
            $salesChannelContext->getSalesChannel()->getId())]
        );

        // Create default billing address id.
        $defaultAddressId = Uuid::randomHex();
        $addressStruct = [
            'id' => $defaultAddressId,
            'customerId' => $dataBag->get('customerId'),
            'title' => $dataBag->get('title'),
            'firstName' => $dataBag->get('firstName'),
            'lastName' => $dataBag->get('firstName'),
            'street' => $dataBag->get('street'),
            'zipcode' => $dataBag->get('zipcode'),
            'city' => $dataBag->get('city'),
            'countryId' => $dataBag->get('countryId'),
        ];

        $dataBag->add(['addresses' => [$addressStruct]]);
        $dataBag->add(['defaultBillingAddressId' => $defaultAddressId]);
        $dataBag->add(['defaultShippingAddressId' => $defaultAddressId]);
        $dataBag->add(['defaultPaymentMethodId' => $salesChannelContext->getPaymentMethod()->getId()]);

        // Set company id.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);
        $companyId = null;

        if($company !== null) {
            $companyId = $company->getId();
        }
        
        $dataBag->add(['myfavOrgCustomerExtension' => [
            'myfavOrgCompanyId' => $companyId,
            'myfavOrgAclRoleId' => $dataBag->get('myfavOrgAclRoleId'),
        ]]);

        $writeContext = clone $salesChannelContext->getContext();
        $writeContext->addState(EntityIndexerRegistry::USE_INDEXING_QUEUE);

       try {
            $this->validateFullFields($dataBag, $salesChannelContext);
            $this->customerRepository->create([$dataBag->all()], $writeContext);
       } catch(\Exception $e) {
            $violations = $e->getViolations();
            $hasErrors = true;
            $errors = $violations;
        }

        return [
            'id' => $customerId,
            'hasErrors' => $hasErrors,
            'errors' => $errors,
        ];
    }

    /**
     * deleteCustomer
     *
     * @param  Context $context
     * @param  string $customerId
     * @return void
     */
    public function deleteCustomer(Context $context, string $customerId): void
    {
        $this->customerRepository->delete([
            [
                'id' => $customerId
            ]
        ], $context);
    }

    /**
     * loadCustomerById
     *
     * @param  Context $context
     * @param  string $customerId
     * @param  string $companyId
     * @return mixed
     */
    public function loadCustomerById(Context $context, string $customerId, string $companyId): mixed
    {
        $criteria = new Criteria();
        $criteria->addAssociation('addresses');
        $criteria->addAssociation('defaultBillingAddress');
        $criteria->addAssociation('defaultShippingAddress');
        $criteria->addFilter(new EqualsFilter('id', $customerId));
        $criteria->addFilter(new EqualsFilter('myfavOrgCustomerExtension.myfavOrgCompanyId', $companyId));
        $customer = $this->customerRepository->search($criteria, $context)->first();

        return $customer;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @return EntitySearchResult
     */
    public function loadList(Context $context, string $companyId, int $currentPage, int $limit, ?string $searchQuery): EntitySearchResult
    {
        $criteria = new Criteria();

        if($searchQuery !== null) {
            $searchParts = explode(' ', $searchQuery);

            $maxSearchParts = 3;
            $searchCount = 0;
            $multiFilterArray = [];

            foreach($searchParts as $searchPart) {
                if($searchCount === $maxSearchParts) {
                    break;
                }

                $multiFilterArray[] = new ContainsFilter('firstName', $searchPart);
                $multiFilterArray[] = new ContainsFilter('lastName', $searchPart);
                $multiFilterArray[] = new ContainsFilter('email', $searchPart);

                $searchCount++;
            }

            if($multiFilterArray > 0) {
                $criteria->addFilter(
                    new MultiFilter(
                        MultiFilter::CONNECTION_OR,
                        $multiFilterArray
                    )
                );
            }
        }

        $criteria->addFilter(new EqualsFilter('myfavOrgCustomerExtension.myfavOrgCompanyId', $companyId));
        $criteria->addSorting(new FieldSorting('lastName', FieldSorting::ASCENDING));
        $criteria->addSorting(new FieldSorting('firstName', FieldSorting::ASCENDING));
        $criteria->setOffset(($currentPage - 1) * $limit);
        $criteria->setLimit($limit);
        $criteria->setTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT);
        $employees = $this->customerRepository->search($criteria, $context);

        return $employees;
    }

    /**
     * setLastLoginDate
     *
     * @param  Context $context
     * @param  string $customerId
     * @return void
     */
    public function setLastLoginDate(Context $context, string $customerId): void
    {
        $this->customerRepository->update([
            [
                'id' => $customerId,
                'lastLogin' => new \DateTimeImmutable(),
            ],
        ], $context);
    }

    /**
     * updateCustomerFromRequest
     *
     * @param  Context $context
     * @param  Request $request
     * @param  SalesChannelContext $salesChannelContext
     * @param  mixed $customer
     * @param  mixed $company
     */
    public function updateCustomerFromRequest(Context $context, DataBag $dataBag, SalesChannelContext $salesChannelContext, mixed $customer, mixed $company): mixed
    {
        $hasErrors = false;
        $errors = [];
        $dataBag->set('active', $dataBag->get('active') == 1 ? true : false);

        // Set billing address.
        $billingAddressId = $customer->getDefaultBillingAddressId();
        $billingAddress = [
            'id' => $billingAddressId,
            'salutationId' => $dataBag->get('billingAddressSalutationId'),
            'title' => $dataBag->get('billingAddressTitle'),
            'firstName' => $dataBag->get('billingAddressFirstName'),
            'lastName' => $dataBag->get('billingAddressLastName'),
            'street' => $dataBag->get('billingAddressStreet'),
            'zipcode' => $dataBag->get('billingAddressZipcode'),
            'city' => $dataBag->get('billingAddressCity'),
            'countryId' => $dataBag->get('billingAddressCountryId'),
        ];
        $addresses = [ $billingAddress ];

        // Set shipping address, if it is an alternative shipping address.
        $shippingAddressId = $billingAddressId;
        $shippingAddress = null;
        
        if($dataBag->get('useAlternativeShippingAddress') == 1) {
            $shippingAddressId = Uuid::randomHex();

            // If the shipping address of a customer is already different to the billing address,
            // we use the existing entry. Otherwise we use the new id from above.
            if($customer->getDefaultBillingAddressId() !== $customer->getDefaultShippingAddressId()) {
                $shippingAddressId = $customer->getDefaultShippingAddressId();
            }
            
            $shippingAddress = [
                'id' => $shippingAddressId,
                'salutationId' => $dataBag->get('shippingAddressSalutationId'),
                'title' => $dataBag->get('shippingAddressTitle'),
                'firstName' => $dataBag->get('shippingAddressFirstName'),
                'lastName' => $dataBag->get('shippingAddressLastName'),
                'street' => $dataBag->get('shippingAddressStreet'),
                'zipcode' => $dataBag->get('shippingAddressZipcode'),
                'city' => $dataBag->get('shippingAddressCity'),
                'countryId' => $dataBag->get('shippingAddressCountryId'),
            ];

            $addresses[] = $shippingAddress;
        }

        // Set customer id.
        $customerDataBag = new DataBag();
        $customerDataBag->add(['id' => $customer->getId()]);
        $customerDataBag->add(['active' => $dataBag->get('active')]);
        $customerDataBag->add(['groupId' => $dataBag->get('groupId')]);
        $customerDataBag->add(['myfavOrgEmployeeExtension' => 
            [ 'myfavOrgAclRoleId' => $dataBag->get('myfavOrgAclRoleId') ]
        ]);
        $customerDataBag->add(['salutationId' => $dataBag->get('salutationId')]);
        $customerDataBag->add(['title' => $dataBag->get('title')]);
        $customerDataBag->add(['firstName' => $dataBag->get('firstName')]);
        $customerDataBag->add(['lastName' => $dataBag->get('lastName')]);
        $customerDataBag->add(['email' => $dataBag->get('email')]);
        $customerDataBag->add(['languageId' => $dataBag->get('languageId')]);

        // Set address data.
        $customerDataBag->add(['defaultBillingAddressId' => $billingAddressId]);
        $customerDataBag->add(['defaultShippingAddressId' => $shippingAddressId]);
        $customerDataBag->add(['addresses' => $addresses]);

        // Update user account, if set.
        // try {
            // Validate email only if it has been changed.
            if($dataBag->get('email') != $customer->getEmail()) {
                $this->validateEmailField($customerDataBag, $salesChannelContext);
            }

            $this->validateUserFields($customerDataBag, $salesChannelContext);
            $this->customerRepository->update([
                $customerDataBag->all()
            ], $context);
        
        /*
        } catch(\Exception $e) {
            $violations = $e->getViolations();
            $hasErrors = true;
            $errors = $violations;
        }
            */

        // Update password, if it is set.
        if(!$hasErrors) {
            try {
                $passwordDataBag = new DataBag();
                $passwordDataBag->add([
                    'id' => $customer->getId(),
                    'password' => $dataBag->get('password')
                ]);

                $this->validatePasswordFields($passwordDataBag, $salesChannelContext);
                $this->customerRepository->update([$passwordDataBag->all()], $context);
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
     * validateEmailField
     *
     * @param  mixed $data
     * @param  mixed $context
     * @return void
     * @throws ConstraintViolationException
     */
    private function validateEmailField(DataBag $data, SalesChannelContext $salesChannelContext): void
    {
        $definition = new DataValidationDefinition('myfav.employee.create.validation');

        // Add E-Mail checks.
        $definition->add('email', new NotBlank(), new Email(null, 'VIOLATION::INVALID_EMAIL_FORMAT_ERROR'));
        
        $this->validator->validate($data->all(), $definition);
        $this->emailUniqueValidation($data, $salesChannelContext);
        $this->emailEmployeeUniqueValidationExceptEditedEmployee($data, $salesChannelContext);
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
        // $definition->add('email', new NotBlank(), new Email(null, 'VIOLATION::INVALID_EMAIL_FORMAT_ERROR'));

        // Add Firstname checks.
        $definition->add('firstName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_FIRST_NAME]));

        // Add Lastname checks.
        $definition->add('lastName', new NotBlank(), new Length(['max' => CustomerDefinition::MAX_LENGTH_LAST_NAME]));
        
        $this->validator->validate($data->all(), $definition);
        // $this->emailUniqueValidation($data, $salesChannelContext);
        // $this->emailEmployeeUniqueValidationExceptEditedEmployee($data, $salesChannelContext);
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