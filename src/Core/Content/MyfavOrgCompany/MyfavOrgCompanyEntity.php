<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgCompany;

use Myfav\Org\Core\Content\MyfavOrgCompanyCustomerGroup\MyfavOrgCompanyCustomerGroupCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgCompanyEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected ?MyfavOrgCompanyCustomerGroupCollection $myfavOrgCompanyCustomerGroups = null;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    // myfavOrgCompanyCustomerGroups
    public function getMyfavOrgCompanyCustomerGroups(): ?MyfavOrgCompanyCustomerGroupCollection
    {
        return $this->myfavOrgCompanyCustomerGroups;
    }

    public function setMyfavOrgCompanyCustomerGroups(?MyfavOrgCompanyCustomerGroupCollection $myfavOrgCompanyCustomerGroups): void
    {
        $this->myfavOrgCompanyCustomerGroups = $myfavOrgCompanyCustomerGroups;
    }
}
