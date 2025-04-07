<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyEntity;
use Shopware\Core\Content\Category\Tree\TreeItem;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class MyfavSalesChannelContextService
{
    /**
     * getCompany
     *
     * @param  SalesChannelContext $salesChannelContext
     * @return null|MyfavOrgCompanyEntity
     */
    public function getCompany(SalesChannelContext $salesChannelContext): ?MyfavOrgCompanyEntity
    {
        // If this is not a company account, but a general user, access for this route is permitted.
        $customer = $salesChannelContext->getCustomer();

        if($customer === null) {
            return null;
        }

        $extensions = $customer->getExtensions();

        if(
            isset($extensions['myfavOrgCustomerExtension']) &&
            isset($extensions['myfavOrgCustomerExtension']['myfavOrgCompany']) &&
            $extensions['myfavOrgCustomerExtension']['myfavOrgCompany'] !== null)
        {
            return $extensions['myfavOrgCustomerExtension']['myfavOrgCompany'];
        }

        return null;
    }
}