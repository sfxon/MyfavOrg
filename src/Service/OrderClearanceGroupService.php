<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Uuid\Uuid;

class OrderClearanceGroupService
{
    public function __construct(private readonly EntityRepository $orderClearanceGroupRepository)
    {
    }

    /**
     * createOrderClearanceGroup
     *
     * @param  Context $context
     * @param  string $name
     */
    public function createOrderClearanceGroup(Context $context, string $name,): string
    {
        $id = Uuid::randomHex();
        $this->orderClearanceGroupRepository->create([
            [
                'id' => $id,
                'name' => $name,
            ]
        ], $context);

        return $id;
    }

    /**
     * deleteOrderClearanceGroup
     *
     * @param  mixed $context
     * @param  mixed $orderClearanceGroupId
     * @return void
     */
    public function deleteOrderClearanceGroup(Context $context, string $orderClearanceGroupId): void
    {
        $this->orderClearanceGroupRepository->delete([
            [
                'id' => $orderClearanceGroupId
            ]
        ], $context);
    }

    /**
     * loadOrderClearanceGroup
     *
     * @param  mixed $context
     * @param  mixed $orderClearanceGroupId
     * @return mixed
     */
    public function loadOrderClearanceGroup(Context $context, string $orderClearanceGroupId): mixed
    {
        $criteria = new Criteria([$orderClearanceGroupId]);
        $orderClearanceGroups = $this->orderClearanceGroupRepository->search($criteria, $context)->first();
        return $orderClearanceGroups;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @return EntitySearchResult
     */
    public function loadList(Context $context): EntitySearchResult
    {
        $orderClearanceGroups = $this->orderClearanceGroupRepository->search(new Criteria(), $context);
        return $orderClearanceGroups;
    }

    /**
     * updateOrderClearanceGroup
     *
     * @param  Context $context
     * @param  string $id
     * @param  string $name
     */
    public function updateOrderClearanceGroup(Context $context, string $id, string $name,): string
    {
        $this->orderClearanceGroupRepository->update([
            [
                'id' => $id,
                'name' => $name,
            ]
        ], $context);

        return $id;
    }
}
