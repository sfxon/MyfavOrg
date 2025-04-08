<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroup;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceGroupEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected ?string $myfavOrgCompanyId;
    protected ?MyfavOrgCompanyEntity $myfavOrgCompany = null;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    // $myfavOrgCompanyId
    public function getMyfavOrgCompanyId(): ?string
    {
        return $this->myfavOrgCompanyId;
    }

    public function setMyfavOrgCompanyId(?string $myfavOrgCompanyId): void
    {
        $this->myfavOrgCompanyId = $myfavOrgCompanyId;
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
}
