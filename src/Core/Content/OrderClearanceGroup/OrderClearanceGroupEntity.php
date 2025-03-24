<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroup;

use Myfav\Org\Core\Content\MyfavOrgEmployee\MyfavOrgEmployeeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceGroupEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected ?MyfavOrgEmployeeCollection $myfavOrgEmployees;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
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
