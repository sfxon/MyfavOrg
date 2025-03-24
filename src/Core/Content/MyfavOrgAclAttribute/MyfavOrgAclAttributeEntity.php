<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclAttributeGroup\MyfavOrgAclAttributeGroupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclAttributeEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $technicalName;
    protected ?string $myfavOrgAclAttributeGroupId;
    protected ?MyfavOrgAclAttributeGroupEntity $myfavOrgAclAttributeGroup;

    // $technicalName
    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(?string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    // $myfavOrgAclAttributeGroupId
    public function getMyfavOrgAclAttributeGroupId(): ?string
    {
        return $this->myfavOrgAclAttributeGroupId;
    }

    public function setMyfavOrgAclAttributeGroupId(?string $myfavOrgAclAttributeGroupId): void
    {
        $this->myfavOrgAclAttributeGroupId = $myfavOrgAclAttributeGroupId;
    }

    // $myfavOrgAclAttributeGroup
    public function getMyfavOrgAclAttributeGroup(): ?MyfavOrgAclAttributeGroupEntity
    {
        return $this->myfavOrgAclAttributeGroup;
    }

    public function setMyfavOrgAclAttributeGroup(?MyfavOrgAclAttributeGroupEntity $myfavOrgAclAttributeGroup): void
    {
        $this->myfavOrgAclAttributeGroup = $myfavOrgAclAttributeGroup;
    }
}
