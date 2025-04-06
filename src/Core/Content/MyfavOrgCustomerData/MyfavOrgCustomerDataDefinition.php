<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgCustomerData;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyDefinition;
use Myfav\Org\Core\Content\MyfavOrgAclRole\MyfavOrgAclRoleDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgCustomerDataDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_customer_data';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class)),
            (new FkField('myfav_org_company_id', 'myfavOrgCompanyId', MyfavOrgCompanyDefinition::class)),
            (new FkField('myfav_org_acl_role_id', 'myfavOrgAclRoleId', MyfavOrgAclRoleDefinition::class)),
            new OneToOneAssociationField('customer', 'customer_id', 'id', CustomerDefinition::class, false)
        ]);
    }
}