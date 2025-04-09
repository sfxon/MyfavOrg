<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroup;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceGroupEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $orderId;
    protected ?string $newStateMachineStateId;
    protected ?string $comment;
    protected ?string $editedByCustomerId;
    protected ?OrderEntity $order = null;
    protected ?CustomerEntity $editedByCustomer = null;
    protected ?StateMachineStateEntity $newStateMachineState = null;

    // $orderId
    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    // $newStateMachineStateId
    public function getNewStateMachineStateId(): ?string
    {
        return $this->newStateMachineStateId;
    }

    public function setNewStateMachineStateId(?string $newStateMachineStateId): void
    {
        $this->newStateMachineStateId = $newStateMachineStateId;
    }

    // $comment
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    // $editedByCustomerId
    public function getEditedByCustomerId(): ?string
    {
        return $this->editedByCustomerId;
    }

    public function setEditedByCustomerId(?string $editedByCustomerId): void
    {
        $this->editedByCustomerId = $editedByCustomerId;
    }

    // $order
    public function getOrder(): ?OrderEntity
    {
        return $this->order;
    }

    public function setOrder(?OrderEntity $order): void
    {
        $this->order = $order;
    }

    // $editedByCustomer
    public function getEditedByCustomer(): ?CustomerEntity
    {
        return $this->editedByCustomer;
    }

    public function setEditedByCustomer(?CustomerEntity $editedByCustomer): void
    {
        $this->editedByCustomer = $editedByCustomer;
    }

    // $newStateMachineState
    public function getNewStateMachineState(): ?StateMachineStateEntity
    {
        return $this->newStateMachineState;
    }

    public function setNewStateMachineState(?StateMachineStateEntity $newStateMachineState): void
    {
        $this->newStateMachineState = $newStateMachineState;
    }
}