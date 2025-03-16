<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclAttribute\MyfavOrgAclAttributeCollection;
use Myfav\Org\Core\Content\MyfavOrgAclRole\MyfavOrgAclRoleCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclRoleAttributeEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $myfavOrgAclRoleId;
    protected ?string $myfavOrgAclAttributeId;
    protected ?\DateTimeImmutable $validFrom;
    protected ?\DateTimeImmutable $validUntil;

    protected ?MyfavOrgAclRoleCollection $myfavOrgAclRoles = null;
    protected ?MyfavOrgAclAttributeCollection $myfavOrgAclAttributes = null;

    // $myfavOrgAclRoleId
    public function getMyfavOrgAclRoleId(): ?string
    {
        return $this->myfavOrgAclRoleId;
    }

    public function setMyfavOrgAclRoleId(?string $myfavOrgAclRoleId): void
    {
        $this->myfavOrgAclRoleId = $myfavOrgAclRoleId;
    }

    // $myfavOrgAclAttributeId
    public function getMyfavOrgAclAttributeId(): ?string
    {
        return $this->myfavOrgAclAttributeId;
    }

    public function setMyfavOrgAclAttributeId(?string $myfavOrgAclAttributeId): void
    {
        $this->myfavOrgAclAttributeId = $myfavOrgAclAttributeId;
    }

    // validFrom
    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function setValidFrom(?\DateTimeImmutable $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    // validUntil
    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeImmutable $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    // myfavOrgAclRoles
    public function getMyfavOrgAclRoles(): ?MyfavOrgAclRoleCollection
    {
        return $this->myfavOrgAclRoles;
    }

    public function setMyfavOrgAclRoles(MyfavOrgAclRoleCollection $myfavOrgAclRoles): void
    {
        $this->myfavOrgAclRoles = $myfavOrgAclRoles;
    }

    // myfavOrgAclAttributes
    public function getMyfavOrgAclAttributes(): ?MyfavOrgAclAttributeCollection
    {
        return $this->myfavOrgAclAttributes;
    }

    public function setMyfavOrgAclAttributes(MyfavOrgAclAttributeCollection $myfavOrgAclAttributes): void
    {
        $this->myfavOrgAclAttributes = $myfavOrgAclAttributes;
    }
}
