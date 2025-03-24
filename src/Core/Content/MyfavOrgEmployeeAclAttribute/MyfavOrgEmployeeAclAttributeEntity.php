<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployeeAclAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclAttribute\MyfavOrgAclAttributeEntity;
use Myfav\Org\Core\Content\MyfavOrgAclAttribute\MyfavOrgAclAttributeCollection;
use Myfav\Org\Core\Content\myfavOrgEmployee\myfavOrgEmployeeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgEmployeeAclAttributeEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $myfavOrgEmployeeId;
    protected ?string $myfavOrgAclAttributeId;
    protected ?\DateTimeImmutable $validFrom;
    protected ?\DateTimeImmutable $validUntil;

    protected ?MyfavOrgAclAttributeEntity $myfavOrgAclAttribute = null;
    protected ?myfavOrgEmployeeCollection $myfavOrgEmployees = null;
    protected ?MyfavOrgAclAttributeCollection $myfavOrgAclAttributes = null;

    // $myfavOrgEmployeeId
    public function getMyfavOrgEmployeeId(): ?string
    {
        return $this->myfavOrgEmployeeId;
    }

    public function setmyfavOrgEmployeeId(?string $myfavOrgEmployeeId): void
    {
        $this->myfavOrgEmployeeId = $myfavOrgEmployeeId;
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

    // myfavOrgEmployees
    public function getmyfavOrgEmployees(): ?myfavOrgEmployeeCollection
    {
        return $this->myfavOrgEmployees;
    }

    public function setmyfavOrgEmployees(myfavOrgEmployeeCollection $myfavOrgEmployees): void
    {
        $this->myfavOrgEmployees = $myfavOrgEmployees;
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

    // myfavOrgAclAttribute
    public function getMyfavOrgAclAttribute(): ?MyfavOrgAclAttributeEntity
    {
        return $this->myfavOrgAclAttribute;
    }

    public function setMyfavOrgAclAttribute(MyfavOrgAclAttributeEntity $myfavOrgAclAttribute): void
    {
        $this->myfavOrgAclAttribute = $myfavOrgAclAttribute;
    }
}
