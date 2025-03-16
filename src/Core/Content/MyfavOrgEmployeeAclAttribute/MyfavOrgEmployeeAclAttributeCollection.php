<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployeeAclAttribute;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgEmployeeAclAttributeEntity $entity)
 * @method void                   set(string $key, MyfavOrgEmployeeAclAttributeEntity $entity)
 * @method MyfavOrgEmployeeAclAttributeEntity[]    getIterator()
 * @method MyfavOrgEmployeeAclAttributeEntity[]    getElements()
 * @method MyfavOrgEmployeeAclAttributeEntity|null get(string $key)
 * @method MyfavOrgEmployeeAclAttributeEntity|null first()
 * @method MyfavOrgEmployeeAclAttributeEntity|null last()
 */
class MyfavOrgEmployeeAclAttributeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgEmployeeAclAttributeEntity::class;
    }
}
