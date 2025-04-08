<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroupCustomer;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Myfav\Org\Core\Content\OrderClearanceRole\OrderClearanceRoleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceGroupCustomerEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $customerId;
    protected ?string $orderClearanceRoleId;
    protected ?CustomerEntity $customer;
    protected ?OrderClearanceRoleEntity $orderClearanceRole;

    // $customerId
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }

    // $orderClearanceRoleId
    public function getOrderClearanceRoleId(): ?string
    {
        return $this->orderClearanceRoleId;
    }

    public function setOrderClearanceRoleId(?string $orderClearanceRoleId): void
    {
        $this->orderClearanceRoleId = $orderClearanceRoleId;
    }

    // $customer
    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }

    // $orderClearanceRole
    public function getOrderClearanceRole(): ?OrderClearanceRoleEntity
    {
        return $this->orderClearanceRole;
    }

    public function setOrderClearanceRole(?OrderClearanceRoleEntity $orderClearanceRole): void
    {
        $this->orderClearanceRole = $orderClearanceRole;
    }
}
