<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\OrderClearanceGroupCustomer;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(OrderClearanceGroupCustomerEntity $entity)
 * @method void                   set(string $key, OrderClearanceGroupCustomerEntity $entity)
 * @method OrderClearanceGroupCustomerEntity[]    getIterator()
 * @method OrderClearanceGroupCustomerEntity[]    getElements()
 * @method OrderClearanceGroupCustomerEntity|null get(string $key)
 * @method OrderClearanceGroupCustomerEntity|null first()
 * @method OrderClearanceGroupCustomerEntity|null last()
 */
class OrderClearanceGroupCustomerCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OrderClearanceGroupCustomerEntity::class;
    }
}
