<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroupEmployee;

use Myfav\Org\Core\Content\MyfavOrgEmployee\MyfavOrgEmployeeEntity;
use Myfav\Org\Core\Content\OrderClearanceRole\OrderClearanceRoleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceGroupEmployeeEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $myfavOrgEmployeeId;
    protected ?string $orderClearanceRoleId;
    protected ?MyfavOrgEmployeeEntity $myfavOrgEmployee;
    protected ?OrderClearanceRoleEntity $orderClearanceRole;

    // $myfavOrgEmployeeId
    public function getMyfavOrgEmployeeId(): ?string
    {
        return $this->myfavOrgEmployeeId;
    }

    public function setMyfavOrgEmployeeId(?string $myfavOrgEmployeeId): void
    {
        $this->myfavOrgEmployeeId = $myfavOrgEmployeeId;
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

    // $myfavOrgEmployee
    public function getMyfavOrgEmployee(): ?MyfavOrgEmployeeEntity
    {
        return $this->myfavOrgEmployee;
    }

    public function setMyfavOrgEmployee(?MyfavOrgEmployeeEntity $myfavOrgEmployee): void
    {
        $this->myfavOrgEmployee = $myfavOrgEmployee;
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
