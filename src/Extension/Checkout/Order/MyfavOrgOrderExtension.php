<?php declare(strict_types=1);

namespace Myfav\Org\Extension\Checkout\Order;

use Myfav\Org\Core\Content\MyfavOrgOrderData\MyfavOrgOrderDataDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgOrderExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(new OneToOneAssociationField('myfavOrgOrderExtension', 'id', 'order_id', MyfavOrgOrderDataDefinition::class, true));
    }

    public function getDefinitionClass(): string
    {
        return OrderDefinition::class;
    }
}