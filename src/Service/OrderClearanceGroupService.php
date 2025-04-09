<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
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
    public function createOrderClearanceGroup(Context $context, string $name, string $companyId): string
    {
        $id = Uuid::randomHex();
        $this->orderClearanceGroupRepository->create([
            [
                'id' => $id,
                'name' => $name,
                'myfavOrgCompanyId' => $companyId
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
     * @param  Context $context
     * @param  string $orderClearanceGroupId
     * @param  string $companyId
     * @return mixed
     */
    public function loadOrderClearanceGroup(Context $context, string $orderClearanceGroupId, string $companyId): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderClearanceGroupId));
        $criteria->addFilter(new EqualsFilter('myfavOrgCompanyId', $companyId));
        $orderClearanceGroups = $this->orderClearanceGroupRepository->search($criteria, $context)->first();
        return $orderClearanceGroups;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @param  string $companyId
     * @return EntitySearchResult
     */
    public function loadList(Context $context, string $companyId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgCompanyId', $companyId));
        $criteria->addSorting(new FieldSorting('name', FieldSorting::ASCENDING));
        $orderClearanceGroups = $this->orderClearanceGroupRepository->search($criteria, $context);
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
