<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRole;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyDefinition;
use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleAttributeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgAclRoleDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_acl_role';

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
        return MyfavOrgAclRoleEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return MyfavOrgAclRoleCollection::class;
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
            (new FkField('myfav_org_company_id', 'myfavOrgCompanyId', MyfavOrgCompanyDefinition::class)),
            (new StringField('name', 'name'))->addFlags(new Required()),

            new ManyToOneAssociationField('myfavOrgCompany', 'myfav_org_company_id', MyfavOrgCompanyDefinition::class, 'id'),
            new OneToManyAssociationField('myfavOrgAclRoleAttributes', MyfavOrgAclRoleAttributeDefinition::class, 'myfav_org_acl_role_id'),
        ]);
    }
}
