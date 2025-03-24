<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceRole;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(OrderClearanceRoleEntity $entity)
 * @method void                   set(string $key, OrderClearanceRoleEntity $entity)
 * @method OrderClearanceRoleEntity[]    getIterator()
 * @method OrderClearanceRoleEntity[]    getElements()
 * @method OrderClearanceRoleEntity|null get(string $key)
 * @method OrderClearanceRoleEntity|null first()
 * @method OrderClearanceRoleEntity|null last()
 */
class OrderClearanceRoleCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OrderClearanceRoleEntity::class;
    }
}
