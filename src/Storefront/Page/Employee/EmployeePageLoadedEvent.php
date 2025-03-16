<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\Employee;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class EmployeePageLoadedEvent extends PageLoadedEvent
{
    /**
     * __construct
     */
    public function __construct(
        private readonly EmployeePage $page,
        SalesChannelContext $salesChannelContext, 
        Request $request)
    {
        parent::__construct($salesChannelContext, $request);
    }

    /**
     * getPage
     *
     * @return EmployeePage
     */
    public function getPage(): EmployeePage
    {
        return $this->page;
    }
}