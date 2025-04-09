<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgOrderData;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyDefinition;
use Myfav\Org\Core\Content\OrderClearanceGroup\OrderClearanceGroupDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgOrderDataDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_order_data';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('order_id', 'orderId', OrderDefinition::class)),
            (new FkField('myfav_org_company_id', 'myfavOrgCompanyId', MyfavOrgCompanyDefinition::class)),
            (new FkField('order_clearance_group_id', 'orderClearanceGroupId', OrderClearanceGroupDefinition::class)),
        ]);
    }
}