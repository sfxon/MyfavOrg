<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceLog;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(OrderClearanceLogEntity $entity)
 * @method void                   set(string $key, OrderClearanceLogEntity $entity)
 * @method OrderClearanceLogEntity[]    getIterator()
 * @method OrderClearanceLogEntity[]    getElements()
 * @method OrderClearanceLogEntity|null get(string $key)
 * @method OrderClearanceLogEntity|null first()
 * @method OrderClearanceLogEntity|null last()
 */
class OrderClearanceLogCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OrderClearanceLogEntity::class;
    }
}
