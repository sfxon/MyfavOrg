<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRole;

use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleAttributeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclRoleEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
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
