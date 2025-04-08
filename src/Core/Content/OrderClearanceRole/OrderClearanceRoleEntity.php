<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceRole;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderClearanceRoleEntity extends Entity
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
