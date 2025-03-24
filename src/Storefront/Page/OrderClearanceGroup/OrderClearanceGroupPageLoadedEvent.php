<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\OrderClearanceGroup;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class OrderClearanceGroupPageLoadedEvent extends PageLoadedEvent
{
    /**
     * __construct
     */
    public function __construct(
        private readonly OrderClearanceGroupPage $page,
        SalesChannelContext $salesChannelContext, 
        Request $request)
    {
        parent::__construct($salesChannelContext, $request);
    }

    /**
     * getPage
     *
     * @return OrderClearanceGroupPage
     */
    public function getPage(): OrderClearanceGroupPage
    {
        return $this->page;
    }
}