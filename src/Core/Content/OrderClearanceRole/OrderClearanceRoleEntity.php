<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceRole;

use Myfav\Org\Core\Content\MyfavOrgEmployee\MyfavOrgEmployeeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceRoleEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $technicalName;
    protected ?MyfavOrgEmployeeCollection $myfavOrgEmployees;

    // $technicalName
    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(?string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    // $myfavOrgEmployees
    public function getMyfavOrgEmployees(): ?MyfavOrgEmployeeCollection
    {
        return $this->myfavOrgEmployees;
    }

    public function setMyfavOrgEmployees(?MyfavOrgEmployeeCollection $myfavOrgEmployees): void
    {
        $this->myfavOrgEmployees = $myfavOrgEmployees;
    }
}
