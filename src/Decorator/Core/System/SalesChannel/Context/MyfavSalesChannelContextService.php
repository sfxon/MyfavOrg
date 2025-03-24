<?php

namespace Myfav\Org\Decorator\Core\System\SalesChannel\Context;

use Myfav\Org\Service\EmployeeService;
use Myfav\Org\Service\MyfavOrgAclLoaderService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceInterface;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceParameters;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

// SalesChannelContextService erweitern, um die employeeId aus der Session zu lesen und in den SalesChannelContext zu übernehmen.
#[AsDecorator(SalesChannelContextService::class)]
class MyfavSalesChannelContextService extends SalesChannelContextService
{
    public function __construct(
        private readonly SalesChannelContextService $decorated,
        private readonly SalesChannelContextPersister $contextPersister,
        private readonly EmployeeService $employeeService,
        private readonly MyfavOrgAclLoaderService $myfavOrgAclLoaderService,
    ) {
    }

    public function get(SalesChannelContextServiceParameters $parameters): SalesChannelContext
    {
        $context = $this->decorated->get($parameters);
        $token = $parameters->getToken();
        $salesChannelId = $parameters->getSalesChannelId();

        // Payload aus der Session laden
        $session = $this->contextPersister->load($token, $salesChannelId);

        // Falls `employeeId` gespeichert wurde, setzen
        if (!empty($session['myfavOrgEmployeeId'])) {
            $context->assign(['myfavOrgEmployeeId' => $session['myfavOrgEmployeeId']]);

            $employee = $this->employeeService->loadById($context->getContext(), $session['myfavOrgEmployeeId']);

            if ($employee) {
                $context->addExtension('myfavOrgEmployee', $employee);

                // Load acl for this employee.
                $employeeAcl = $this->myfavOrgAclLoaderService->loadEmployeesAclAttributes($context->getContext(), $employee);
                $context->addExtension('myfavOrgEmployeeAcl', new ArrayStruct($employeeAcl));
            }
        }

        return $context;
    }
}
