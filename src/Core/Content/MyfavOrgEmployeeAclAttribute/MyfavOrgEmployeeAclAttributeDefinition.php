<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployeeAclAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclAttribute\MyfavOrgAclAttributeDefinition;
use Myfav\Org\Core\Content\MyfavOrgEmployee\MyfavOrgEmployeeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
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

class MyfavOrgEmployeeAclAttributeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_employee_acl_attribute';

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
        return MyfavOrgEmployeeAclAttributeEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return MyfavOrgEmployeeAclAttributeCollection::class;
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
            (new FkField('myfav_org_employee_id', 'myfavOrgEmployeeId', MyfavOrgEmployeeDefinition::class))->addFlags(new Required()),
            (new FkField('myfav_org_acl_attribute_id', 'myfavOrgAclAttributeId', MyfavOrgAclAttributeDefinition::class))->addFlags(new Required()),
            (new DateTimeField('valid_from', 'validFrom')),
            (new DateTimeField('valid_until', 'validUntil')),

            new ManyToOneAssociationField('myfavOrgEmployee', 'myfav_org_employee_id', MyfavOrgEmployeeDefinition::class, 'id'),
            new ManyToOneAssociationField('myfavOrgAclAttribute', 'myfav_org_acl_attribute_id', MyfavOrgAclAttributeDefinition::class, 'id'),
        ]);
    }
}
