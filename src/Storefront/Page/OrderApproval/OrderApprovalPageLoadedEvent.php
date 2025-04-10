<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\OrderApproval;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class OrderApprovalPageLoadedEvent extends PageLoadedEvent
{
    /**
     * __construct
     */
    public function __construct(
        private readonly OrderApprovalPage $page,
        SalesChannelContext $salesChannelContext, 
        Request $request)
    {
        parent::__construct($salesChannelContext, $request);
    }

    /**
     * getPage
     *
     * @return OrderApprovalPage
     */
    public function getPage(): OrderApprovalPage
    {
        return $this->page;
    }
}