<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute;

use Myfav\Org\Core\Content\MyfavOrgAclRoleAttribute\MyfavOrgAclRoleAttributeEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgAclRoleAttributeEntity $entity)
 * @method void                   set(string $key, MyfavOrgAclRoleAttributeEntity $entity)
 * @method MyfavOrgAclRoleAttributeEntity[]    getIterator()
 * @method MyfavOrgAclRoleAttributeEntity[]    getElements()
 * @method MyfavOrgAclRoleAttributeEntity|null get(string $key)
 * @method MyfavOrgAclRoleAttributeEntity|null first()
 * @method MyfavOrgAclRoleAttributeEntity|null last()
 */
class MyfavOrgAclRoleAttributeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgAclRoleAttributeEntity::class;
    }
}