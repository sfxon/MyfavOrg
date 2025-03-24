<?php

namespace Myfav\Org\Decorator\Core\System\SalesChannel\Context;


use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceParameters;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// SalesChannelContextService erweitern, um die employeeId aus der Session zu lesen und in den SalesChannelContext zu übernehmen.
class MyfavSalesChannelContextService extends SalesChannelContextService
{
    public function __construct(
        private readonly SalesChannelContextService $decorated,
        private readonly SalesChannelContextPersister $contextPersister
    ) {
    }

    public function get(SalesChannelContextServiceParameters $parameters): SalesChannelContext
    {
        $context = $this->decorated->get($parameters);

        // Employee-ID aus Session holen
        $sessionData = $this->contextPersister->load($parameters->getToken(), $parameters->getSalesChannelId());
        $employeeId = $sessionData['myfav_org_employee_id'] ?? null;

        if ($employeeId) {
            $context->assign([
                'myfavOrgEmployeeId' => $employeeId
            ]);
        }

        return $context;
    }
}
