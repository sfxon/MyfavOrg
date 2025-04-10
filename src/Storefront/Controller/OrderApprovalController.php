<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Service\AccessRightsService;
use Myfav\Org\Service\OrderClearanceGroupService;
use Myfav\Org\Service\MyfavSalesChannelContextService;
use Myfav\Org\Storefront\Page\OrderApproval\OrderApprovalPageLoader;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class OrderApprovalController extends StorefrontController
{
    /**
     * __construct
     */
    public function __construct(
        private readonly AccessRightsService $accessRightsService,
        private readonly MyfavSalesChannelContextService $myfavSalesChannelContextService,
        private readonly OrderApprovalPageLoader $orderApprovalPageLoader,
        private readonly RouterInterface $router,
        private readonly OrderClearanceGroupService $orderClearanceGroupService,
    )
    {
    }

    #[Route(path: '/myfav/org/order/approval/list', name: 'myfav.org.order.approval.list', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function listOrders(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderapproval.read', 'frontend.account.home.page');

        // Load company - this is also used to validate, that this is a company account.
        // Only company accounts can use the org modules.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.aclrole.list');
            return new RedirectResponse($url);
        }

        $page = $this->orderApprovalPageLoader->load($request, $salesChannelContext);

        die('get orders that have to be cleared.');
        
        /*
        $orderClearanceGroups = $this->orderClearanceGroupService->loadList($context, $company->getId());

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/order-clearance-group/index.html.twig', [
            'successMessage' => $request->query->get('successMessage'),
            'orderClearanceGroups' => $orderClearanceGroups,
            'page' => $page,
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.delete'),
        ]);
        */
    }
}