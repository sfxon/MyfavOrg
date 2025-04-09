<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRole;

use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleAttributeCollection;
use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclRoleEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected ?string $myfavOrgCompanyId;
    protected ?MyfavOrgCompanyEntity $myfavOrgCompany = null;
    protected ?MyfavOrgAclRoleAttributeCollection $myfavOrgAclRoleAttributes = null;

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

    // myfavOrgAclRoleAttributes
    public function getMyfavOrgAclRoleAttributes(): ?MyfavOrgAclRoleAttributeCollection
    {
        return $this->myfavOrgAclRoleAttributes;
    }

    public function setMyfavOrgAclRoleAttributes(?MyfavOrgAclRoleAttributeCollection $myfavOrgAclRoleAttributes): void
    {
        $this->myfavOrgAclRoleAttributes = $myfavOrgAclRoleAttributes;
    }

    public function getAttributesIndexByAttributeId(): array
    {
        $retval = [];

        if($this->myfavOrgAclRoleAttributes === null) {
            return $retval;
        }

        foreach($this->myfavOrgAclRoleAttributes as $roleAttribute) {
            $retval[$roleAttribute->getMyfavOrgAclAttributeId()] = $roleAttribute;
        }

        return $retval;
    }

    public function getAttributesIndexByTechnicalName(): array
    {
        $retval = [];

        if($this->myfavOrgAclRoleAttributes === null) {
            return $retval;
        }

        foreach($this->myfavOrgAclRoleAttributes as $roleAttribute) {
            $retval[$roleAttribute->getMyfavOrgAclAttribute()->getTechnicalName()] = $roleAttribute;
        }

        return $retval;
    }
}