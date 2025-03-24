<?php

namespace Myfav\Org\Decorator\Core\System\SalesChannel\Context;

use DateTime;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use JsonException;
use Shopware\Core\Checkout\Cart\AbstractCartPersister;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MyfavSalesChannelContextPersister extends SalesChannelContextPersister
{
    private readonly string $lifetimeInterval;

    public function __construct(
        private readonly SalesChannelContextPersister $decorated,
        private readonly Connection $connection,
        private readonly EventDispatcherInterface $eventDispatcher,
        AbstractCartPersister $cartPersister,
        ?string $lifetimeInterval = 'P1D'
    ) {
        parent::__construct($connection, $eventDispatcher, $cartPersister, $lifetimeInterval);
        $this->lifetimeInterval = $lifetimeInterval ?? 'P1D';
    }

    public function save(string $token, array $newParameters, string $salesChannelId, ?string $customerId = null): void
    {
        

        // Employee ID hinzufügen, falls vorhanden
        if (!empty($newParameters['myfavOrgEmployeeId'])) {
            $existing = $this->load($token, $salesChannelId, $customerId);
            $existing['myfavOrgEmployeeId'] = $newParameters['myfavOrgEmployeeId'];
            $newParameters = $existing;
        }

        parent::save($token, $newParameters, $salesChannelId, $customerId);
    }
}
