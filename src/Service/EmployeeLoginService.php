<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EmployeeLoginService
{
    public function __construct(
        private readonly Connection $connection,
        private readonly CustomerService $customerService,
        private readonly EmployeeService $employeeService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly SalesChannelContextPersister $contextPersister,)
    {
    }

    /**
     * loginByCredentials
     *
     * @param  string $email
     * @param  string $password
     * @param  SalesChannelContext $salesChannelContext
     * @return mixed
     */
    public function loginByCredentials(string $email, string $password, SalesChannelContext $salesChannelContext): mixed
    {
        $context = $salesChannelContext->getContext();

        if ($email === '' || $password === '') {
            return false;
        }

        $employee = $this->employeeService->loadByEmail($context, $email);

        if (
            $employee === null ||
            $employee->isActive() === false ||
            $employee->getPassword() === null ||
            !password_verify($password, $employee->getPassword())) {
            // Do default login here.
            return false;
        }

        // Get customer and login.
        $customerId = $employee->getCustomerId();
        $employeeId = $employee->getId();
        $salesChannelId = $salesChannelContext->getSalesChannelId();
        $customer = $this->customerService->loadCustomerById($context, $customerId);
        $this->customerService->setLastLoginDate($context, $customerId);

        // Try to load a context with the given customerId and myfavOrgEmployeeId.
        $employeeToken = $this->loadSalesChannelContextWithEmployee($context, $customerId, $employeeId);

        if(null === $employeeToken) {
            //$employeeToken = Uuid::randomHex();
            $employeeToken = bin2hex(random_bytes(16));
            $parameters = [
                'expired' => false,
                'customerId' => $customerId,
                'myfavOrgEmployeeId' => $employeeId,
                'billingAddressId' => null,
                'shippingAddressId' => null,
                'guest' => false
            ];
            $this->saveSalesChannelContext($employeeToken, $parameters, $salesChannelId, $customerId);
            // $this->contextPersister->save($employeeToken, ['myfavOrgEmployeeId' => $employeeId], $salesChannelId, $customerId);
        } else {
            $event = new CustomerLoginEvent($salesChannelContext, $customer, $employeeToken);
            $this->eventDispatcher->dispatch($event);

            return new ContextTokenResponse($employeeToken);
        }

        $event = new CustomerLoginEvent($salesChannelContext, $customer, $employeeToken);
        $this->eventDispatcher->dispatch($event);

        return new ContextTokenResponse($employeeToken);
    }

    /**
     * removeEmployeeFromSession
     *
     * @param  string $token
     * @param  SalesChannelContext $salesChannelContext
     * @return void
     */
    public function removeEmployeeFromSession(string $token, SalesChannelContext $salesChannelContext)
    {
        $salesChannelId = $salesChannelContext->getSalesChannelId();
        $session = $this->contextPersister->load($token, $salesChannelContext->getSalesChannelId());
        $this->contextPersister->save($token, ['myfavOrgEmployeeId' => null], $salesChannelId, $session['customerId']);
    }
    
    /**
     * loadSalesChannelContextWithEmployee
     *
     * @param  Context $context
     * @param  string $customerId
     * @param  string $myfavOrgEmployeeId
     * @return null|string
     */
    private function loadSalesChannelContextWithEmployee(Context $context, string $customerId, string $myfavOrgEmployeeId): ?string
    {
        // Lade alle Einträge aus SalesChannelApiContext, bei denen die CustomerID gleich der aktuellen Customer-ID ist.
        $query = 
            $this->connection->createQueryBuilder()
            ->select('*, JSON_UNQUOTE(JSON_EXTRACT(payload, "$.myfavOrgEmployeeId")) AS employee_id')
            ->from('sales_channel_api_context')
            ->where('customer_id is null')
            ->setParameter('customerId', Uuid::fromHexToBytes($customerId));

        $salesChannelContextEntries = $query->executeQuery()->fetchAllAssociative();

        foreach($salesChannelContextEntries as $salesChannelContextEntry) {
            if($myfavOrgEmployeeId === $salesChannelContextEntry['employee_id']) {
                return $salesChannelContextEntry['token'];
            }
        }

        return null;
    }

    /**
     * saveSalesChannelContext
     *
     * @param  mixed $token
     * @param  mixed $newParameters
     * @param  mixed $salesChannelId
     * @param  mixed $customerId
     * @return void
     */
    private function saveSalesChannelContext(string $token, array $newParameters, string $salesChannelId, ?string $customerId = null): void
    {
        $parameters = $newParameters;
        unset($parameters['token']);

        $this->connection->executeStatement(
            'INSERT INTO sales_channel_api_context (`token`, `payload`, `sales_channel_id`, `customer_id`, `updated_at`)
                VALUES (:token, :payload, :salesChannelId, :customerId, :updatedAt)',
            [
                'token' => $token,
                'payload' => json_encode($parameters, \JSON_THROW_ON_ERROR),
                'salesChannelId' => $salesChannelId ? Uuid::fromHexToBytes($salesChannelId) : null,
                'customerId' => null,//$customerId ? Uuid::fromHexToBytes($customerId) : null,
                'updatedAt' => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );
    }
}