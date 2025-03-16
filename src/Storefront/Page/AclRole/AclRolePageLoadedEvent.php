<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\AclRole;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class AclRolePageLoadedEvent extends PageLoadedEvent
{
    /**
     * __construct
     */
    public function __construct(
        private readonly AclRolePage $page,
        SalesChannelContext $salesChannelContext, 
        Request $request)
    {
        parent::__construct($salesChannelContext, $request);
    }

    /**
     * getPage
     *
     * @return AclRolePage
     */
    public function getPage(): AclRolePage
    {
        return $this->page;
    }
}