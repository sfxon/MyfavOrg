<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroupCustomer;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Myfav\Org\Core\Content\OrderClearanceGroup\OrderClearanceGroupDefinition;
use Myfav\Org\Core\Content\OrderClearanceRole\OrderClearanceRoleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class OrderClearanceGroupCustomerDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'order_clearance_group_customer';

    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return OrderClearanceGroupCustomerEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return OrderClearanceGroupCustomerCollection::class;
    }

    /**
     * defineFields
     *
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey(), new ApiAware()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new Required()),
            (new FkField('order_clearance_group_id', 'orderClearanceGroupId', OrderClearanceGroupDefinition::class))->addFlags(new Required()),
            (new FkField('order_clearance_role_id', 'orderClearanceRoleId', OrderClearanceRoleDefinition::class))->addFlags(new Required()),

            new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id'),
            new ManyToOneAssociationField('orderClearanceGroupId', 'order_clearance_group_id', OrderClearanceGroupDefinition::class, 'id'),
            new ManyToOneAssociationField('orderClearanceRoleId', 'order_clearance_role_id', OrderClearanceRoleDefinition::class, 'id'),
        ]);
    }
}
