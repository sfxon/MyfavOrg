<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttributeGroup;

use Myfav\Org\Core\Content\MyfavOrgAclAttribute\MyfavOrgAclAttributeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclAttributeGroupEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected ?MyfavOrgAclAttributeCollection $myfavOrgAclAttributes;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    // $myfavOrgAclAttributes
    public function getMyfavOrgAclAttributes(): ?MyfavOrgAclAttributeCollection
    {
        return $this->myfavOrgAclAttributes;
    }

    public function setMyfavOrgAclAttributes(?MyfavOrgAclAttributeCollection $myfavOrgAclAttributes): void
    {
        $this->myfavOrgAclAttributes = $myfavOrgAclAttributes;
    }
}
