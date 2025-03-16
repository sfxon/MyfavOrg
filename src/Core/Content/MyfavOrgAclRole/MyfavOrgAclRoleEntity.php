<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRole;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclRoleEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
