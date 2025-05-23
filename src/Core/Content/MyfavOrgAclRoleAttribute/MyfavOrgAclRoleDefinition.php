<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclAttributeDefinition;
use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleDefinition;
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
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MyfavOrgAclRoleAttributeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_acl_role_attribute';

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
        return PimImageEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return PimImageCollection::class;
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
            (new FkField('myfav_org_acl_role_id', 'myfavOrgAclRoleId', MyfavOrgAclRoleDefinition::class))->addFlags(new Required()),
            (new FkField('myfav_org_acl_attribute_id', 'myfavOrgAclAttributeId', MyfavOrgAclAtributeDefinition::class))->addFlags(new Required()),
            (new DateField('valid_from', 'validFrom')),
            (new DateField('valid_until', 'valid_until')),

            new ManyToOneAssociationField('myfav_org_acl_role', 'myfav_org_acl_role_id', MyfavOrgAclRoleDefinition::class, 'id'),
            new ManyToOneAssociationField('myfav_org_acl_attribute', 'myfav_org_acl_attribute_id', MyfavOrgAclAtributeDefinition::class, 'id'),
        ]);
    }
}
