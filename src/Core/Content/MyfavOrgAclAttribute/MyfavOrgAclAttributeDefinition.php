<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclAttributeGroup\MyfavOrgAclAttributeGroupDefinition;
use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleAttributeDefinition;
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

class MyfavOrgAclAttributeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_acl_attribute';

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
        return MyfavOrgAclAttributeEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return MyfavOrgAclAttributeCollection::class;
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
            (new StringField('technical_name', 'technicalName'))->addFlags(new Required()),
            (new FkField('myfav_org_acl_attribute_group_id', 'myfavOrgAclAttributeGroupId', MyfavOrgAclAttributeGroupDefinition::class))->addFlags(new Required()),

            new OneToManyAssociationField('myfavOrgAclRoleAttributes', MyfavOrgAclRoleAttributeDefinition::class, 'myfav_org_acl_attribute_id'),
            new ManyToOneAssociationField('myfavOrgAclAttributeGroup', 'myfav_org_acl_attribute_group_id', MyfavOrgAclAttributeGroupDefinition::class, 'id'),
        ]);
    }
}
