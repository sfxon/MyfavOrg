<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\OrderClearanceGroup;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderClearanceGroupPageLoader
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
     * @return OrderClearanceGroupPage
     */
    public function load(Request $request, SalesChannelContext $context): OrderClearanceGroupPage
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = OrderClearanceGroupPage::createFrom($page);

        // Do additional stuff, e.g. load more data from store api and add it to page
        // $page->setExampleData(...);

        $this->eventDispatcher->dispatch(
            new OrderClearanceGroupPageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}