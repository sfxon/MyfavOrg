<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceRole;

use Myfav\Org\Core\Content\MyfavOrgEmployee\MyfavOrgEmployeeDefinition;
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

class OrderClearanceRoleDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'order_clearance_role';

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
        return OrderClearanceRoleEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return OrderClearanceRoleCollection::class;
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
            (new StringField('technicalName', 'technicalName'))->addFlags(new Required()),
            (new OneToManyAssociationField('myfavOrgEmployees', MyfavOrgEmployeeDefinition::class, 'order_clearance_role_id'))
        ]);
    }
}
