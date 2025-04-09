<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceLog;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class OrderClearanceLogDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'order_clearance_log';

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
        return OrderClearanceLogEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return OrderClearanceLogCollection::class;
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
            (new FkField('order_id', 'orderId', OrderDefinition::class)),
            (new FkField('new_state_machine_state_id', 'newStateMachineStateId', StateMachineStateDefinition::class)),
            (new StringField('comment', 'comment'))->addFlags(new Required()),
            (new FkField('edited_by_customer_id', 'editedByCustomerId', CustomerDefinition::class)),

            new ManyToOneAssociationField('order', 'order_id', OrderDefinition::class, 'id'),
            new ManyToOneAssociationField('newStateMachineState', 'new_state_machine_state_id', StateMachineStateDefinition::class, 'id'),
            new ManyToOneAssociationField('editedByCustomer', 'editedByCustomerId', CustomerDefinition::class, 'id'),
        ]);
    }
}
