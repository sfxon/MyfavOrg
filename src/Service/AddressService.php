<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class AddressService
{
    public function __construct(private readonly EntityRepository $customerAddressRepository)
    {
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @param  string $customerId
     * @return EntitySearchResult
     */
    public function loadList(Context $context, string $customerId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customerId', $customerId));
        $addresses = $this->customerAddressRepository->search($criteria, $context);
        return $addresses;
    }
}
