<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRole;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgAclRoleEntity $entity)
 * @method void                   set(string $key, MyfavOrgAclRoleEntity $entity)
 * @method MyfavOrgAclRoleEntity[]    getIterator()
 * @method MyfavOrgAclRoleEntity[]    getElements()
 * @method MyfavOrgAclRoleEntity|null get(string $key)
 * @method MyfavOrgAclRoleEntity|null first()
 * @method MyfavOrgAclRoleEntity|null last()
 */
class MyfavOrgAclRoleCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgAclRoleEntity::class;
    }
}