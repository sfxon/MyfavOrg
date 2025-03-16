<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\Employee;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class EmployeePageLoader
{
    private GenericPageLoaderInterface $genericPageLoader;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * __construct
     *
     * @param  mixed $genericPageLoader
     * @param  mixed $eventDispatcher
     * @return void
     */
    public function __construct(GenericPageLoaderInterface $genericPageLoader, EventDispatcherInterface $eventDispatcher)
    {
        $this->genericPageLoader = $genericPageLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * load
     *
     * @param  mixed $request
     * @param  mixed $context
     * @return EmployeePage
     */
    public function load(Request $request, SalesChannelContext $context): EmployeePage
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = EmployeePage::createFrom($page);

        // Do additional stuff, e.g. load more data from store api and add it to page
        // $page->setExampleData(...);

        $this->eventDispatcher->dispatch(
            new EmployeePageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}