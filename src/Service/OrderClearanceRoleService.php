<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;

class OrderClearanceRoleService
{
    public function __construct(private readonly EntityRepository $orderClearanceRoleRepository)
    {
    }

    /**
     * createorderClearanceRole
     *
     * @param  Context $context
     * @param  string $name
     */
    public function createOrderClearanceRole(Context $context, string $name): string
    {
        $id = Uuid::randomHex();
        $this->orderClearanceRoleRepository->create([
            [
                'id' => $id,
                'name' => $name
            ]
        ], $context);

        return $id;
    }

    /**
     * deleteOrderClearanceRole
     *
     * @param  mixed $context
     * @param  mixed $orderClearanceRoleId
     * @return void
     */
    public function deleteOrderClearanceRole(Context $context, string $orderClearanceRoleId): void
    {
        $this->orderClearanceRoleRepository->delete([
            [
                'id' => $orderClearanceRoleId
            ]
        ], $context);
    }

    /**
     * loadOrderClearanceRole
     *
     * @param  Context $context
     * @param  string $orderClearanceRoleId
     * @return mixed
     */
    public function loadOrderClearanceRole(Context $context, string $orderClearanceRoleId): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderClearanceRoleId));
        $orderClearanceRoles = $this->orderClearanceRoleRepository->search($criteria, $context)->first();
        return $orderClearanceRoles;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @return EntitySearchResult
     */
    public function loadList(Context $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('name', FieldSorting::ASCENDING));
        $orderClearanceRoles = $this->orderClearanceRoleRepository->search($criteria, $context);
        return $orderClearanceRoles;
    }

    /**
     * updateOrderClearanceRole
     *
     * @param  Context $context
     * @param  string $id
     * @param  string $name
     */
    public function updateOrderClearanceRole(Context $context, string $id, string $name,): string
    {
        $this->orderClearanceRoleRepository->update([
            [
                'id' => $id,
                'name' => $name,
            ]
        ], $context);

        return $id;
    }
}
