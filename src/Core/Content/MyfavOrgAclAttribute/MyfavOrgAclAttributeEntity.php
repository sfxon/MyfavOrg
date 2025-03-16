<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttribute;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavOrgAclAttributeEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $technicalName;

    // $technicalName
    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(?string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }
}
