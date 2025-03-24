<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(OrderClearanceGroupEntity $entity)
 * @method void                   set(string $key, OrderClearanceGroupEntity $entity)
 * @method OrderClearanceGroupEntity[]    getIterator()
 * @method OrderClearanceGroupEntity[]    getElements()
 * @method OrderClearanceGroupEntity|null get(string $key)
 * @method OrderClearanceGroupEntity|null first()
 * @method OrderClearanceGroupEntity|null last()
 */
class OrderClearanceGroupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OrderClearanceGroupEntity::class;
    }
}
