<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Page\OrderApproval;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderApprovalPageLoader
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
     * @return OrderApprovalPage
     */
    public function load(Request $request, SalesChannelContext $context): OrderApprovalPage
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = OrderApprovalPage::createFrom($page);

        $this->eventDispatcher->dispatch(
            new OrderApprovalPageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}