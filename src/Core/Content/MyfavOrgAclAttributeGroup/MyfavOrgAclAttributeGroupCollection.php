<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttributeGroup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgAclAttributeGroupEntity $entity)
 * @method void                   set(string $key, MyfavOrgAclAttributeGroupEntity $entity)
 * @method MyfavOrgAclAttributeGroupEntity[]    getIterator()
 * @method MyfavOrgAclAttributeGroupEntity[]    getElements()
 * @method MyfavOrgAclAttributeGroupEntity|null get(string $key)
 * @method MyfavOrgAclAttributeGroupEntity|null first()
 * @method MyfavOrgAclAttributeGroupEntity|null last()
 */
class MyfavOrgAclAttributeGroupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgAclAttributeGroupEntity::class;
    }
}
