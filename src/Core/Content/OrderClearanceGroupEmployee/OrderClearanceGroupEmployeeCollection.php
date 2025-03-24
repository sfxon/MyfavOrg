<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroupEmployee;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(OrderClearanceGroupEmployeeEntity $entity)
 * @method void                   set(string $key, OrderClearanceGroupEmployeeEntity $entity)
 * @method OrderClearanceGroupEmployeeEntity[]    getIterator()
 * @method OrderClearanceGroupEmployeeEntity[]    getElements()
 * @method OrderClearanceGroupEmployeeEntity|null get(string $key)
 * @method OrderClearanceGroupEmployeeEntity|null first()
 * @method OrderClearanceGroupEmployeeEntity|null last()
 */
class OrderClearanceGroupEmployeeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OrderClearanceGroupEmployeeEntity::class;
    }
}
