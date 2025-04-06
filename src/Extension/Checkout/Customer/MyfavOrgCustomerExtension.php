<?php declare(strict_types=1);

namespace Myfav\Org\Extension\Checkout\Customer;

use Myfav\Org\Core\Content\MyfavOrgCustomerData\MyfavOrgCustomerDataDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgCustomerExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(new OneToOneAssociationField('myfavOrgCustomerExtension', 'id', 'customer_id', MyfavOrgCustomerDataDefinition::class, true));
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }
}