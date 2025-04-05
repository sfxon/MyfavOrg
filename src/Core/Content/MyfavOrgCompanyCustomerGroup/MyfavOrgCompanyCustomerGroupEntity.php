<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgCompanyCustomerGroup;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyEntity;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgCompanyCustomerGroupEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $myfavOrgCustomerId;
    protected ?string $customerGroupId;
    protected ?MyfavOrgCompanyEntity $myfavOrgCompany;
    protected ?CustomerGroupEntity $customerGroup;

    // $myfavOrgCustomerId
    public function getMyfavOrgCustomerId(): ?string
    {
        return $this->myfavOrgCustomerId;
    }

    public function setMyfavOrgCustomerId(?string $myfavOrgCustomerId): void
    {
        $this->myfavOrgCustomerId = $myfavOrgCustomerId;
    }

    // $customerGroupId
    public function getCustomerGroupId(): ?string
    {
        return $this->customerGroupId;
    }

    public function setCustomerGroupId(?string $customerGroupId): void
    {
        $this->customerGroupId = $customerGroupId;
    }

    // $myfavOrgCompany
    public function getMyfavOrgCompany(): ?MyfavOrgCompanyEntity
    {
        return $this->myfavOrgCompany;
    }

    public function setMyfavOrgCompany(?MyfavOrgCompanyEntity $myfavOrgCompany): void
    {
        $this->myfavOrgCompany = $myfavOrgCompany;
    }

    // $customerGroup
    public function getCustomerGroup(): ?CustomerGroupEntity
    {
        return $this->customerGroup;
    }

    public function setCustomerGroup(?CustomerGroupEntity $customerGroup): void
    {
        $this->customerGroup = $customerGroup;
    }
}
