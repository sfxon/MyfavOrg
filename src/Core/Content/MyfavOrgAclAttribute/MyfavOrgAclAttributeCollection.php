<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclAttribute;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgAclAttributeEntity $entity)
 * @method void                   set(string $key, MyfavOrgAclAttributeEntity $entity)
 * @method MyfavOrgAclAttributeEntity[]    getIterator()
 * @method MyfavOrgAclAttributeEntity[]    getElements()
 * @method MyfavOrgAclAttributeEntity|null get(string $key)
 * @method MyfavOrgAclAttributeEntity|null first()
 * @method MyfavOrgAclAttributeEntity|null last()
 */
class MyfavOrgAclAttributeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgAclAttributeEntity::class;
    }
}
