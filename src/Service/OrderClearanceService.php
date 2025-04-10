<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;

class OrderClearanceService
{
    public function __construct(private readonly EntityRepository $orderRepository)
    {
    }

    /**
     * loadOrders
     *
     * @param  Context $context
     * @return EntitySearchResult
     */
    public function loadOrders(
        Context $context,
        string $myfavOrgCompanyId,
        string $orderClearanceGroupId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgOrderExtension.myfavOrgCompanyId', $myfavOrgCompanyId));
        $criteria->addFilter(new EqualsFilter('myfavOrgOrderExtension.orderClearanceGroupId', $orderClearanceGroupId));
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::ASCENDING)); // Show oldest orders first.
        $orders = $this->orderRepository->search($criteria, $context);
        return $orders;
    }
}
