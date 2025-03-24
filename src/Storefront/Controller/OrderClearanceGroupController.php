<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Service\AccessRightsService;
use Myfav\Org\Service\OrderClearanceGroupService;
use Myfav\Org\Storefront\Page\OrderClearanceGroup\OrderClearanceGroupPageLoader;
use Shopware\Core\Content\Media\Pathname\PathnameStrategy\PathnameStrategyInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\RepositoryIterator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\Routing\RouterInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class OrderClearanceGroupController extends StorefrontController
{
    /**
     * __construct
     */
    public function __construct(
        private readonly AccessRightsService $accessRightsService,
        private readonly OrderClearanceGroupPageLoader $orderClearanceGroupPageLoader,
        private readonly RouterInterface $router,
        private readonly OrderClearanceGroupService $orderClearanceGroupService,
    )
    {
    }

    #[Route(path: '/myfav/org/order-clearance-group/create', name: 'myfav.org.orderclearancegroup.create', methods: ['POST'], defaults: ['XmlHttpRequest' => 'true'])]
    public function createOrderClearanceGroup(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegroup.create', 'myfav.org.orderclearancegroup.list');
        $this->orderClearanceGroupService->createOrderClearanceGroup($context, $request->request->get('name'));
        $url = $this->router->generate('myfav.org.orderclearancegroup.list', [ 'successMessage'=> 'createdOrderClearanceGroup' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/order-clearance-group/list', name: 'myfav.org.orderclearancegroup.list', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function listOrderClearanceGroup(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegropu', 'frontend.account.home.page');
        $page = $this->orderClearanceGroupPageLoader->load($request, $salesChannelContext);
        $orderClearanceGroups = $this->orderClearanceGroupService->loadList($context);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/order-clearance-group/index.html.twig', [
            'successMessage' => $request->query->get('successMessage'),
            'orderClearanceGroups' => $orderClearanceGroups,
            'page' => $page,
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/order-clearance-group/delete', name: 'myfav.org.orderclearancegroup.delete', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function deleteOrderClearanceGroup(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegroup.delete', 'myfav.org.orderclearancegroup.list');
        $orderClearanceGroupId = $request->query->get('orderClearanceGroupId');
        $this->orderClearanceGroupService->deleteOrderClearanceGroup($context, $orderClearanceGroupId);

        $url = $this->router->generate('myfav.org.orderclearancegroup.list', [ 'successMessage'=> 'deletedOrderClearanceGroup' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/order-clearance-group/edit', name: 'myfav.org.orderclearancegroup.edit', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function editOrderClearanceGroup(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegroup.update', 'myfav.org.orderclearancegroup.list');
        $page = $this->orderClearanceGroupPageLoader->load($request, $salesChannelContext);
        $orderClearanceGroupId = $request->query->get('orderClearanceGroupId');
        $orderClearanceGroup = $this->orderClearanceGroupService->loadOrderClearanceGroup($context, $orderClearanceGroupId);

        // Todo: Security check. Check if this role belongs to this account!

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/order-clearance-group/edit.html.twig', [
            'page' => $page,
            'orderClearanceGroup' => $orderClearanceGroup,
            'editMode' => 'edit',
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'role.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/order-clearance-group/new', name: 'myfav.org.orderclearancegroup.new', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function newRole(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegroup.create', 'frontend.account.home.page');
        $page = $this->orderClearanceGroupPageLoader->load($request, $salesChannelContext);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/order-clearance-group/edit.html.twig', [
            'page' => $page,
            'editMode' => 'new',
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'orderclearancegroup.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/order-clearance-group/update', name: 'myfav.org.orderclearancegroup.update', methods: ['POST'], defaults: ['XmlHttpRequest' => 'true'])]
    public function updateRole(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'orderclearancegroup.update', 'myfav.org.orderclearancegroup.list');
        $orderClearanceGroupId = $request->query->get('orderClearanceGroupId');
        $this->orderClearanceGroupService->updateOrderClearanceGroup($context, $orderClearanceGroupId, $request->request->get('name'));

        $url = $this->router->generate('myfav.org.orderclearancegroup.list', [ 'successMessage'=> 'updatedOrderClearanceGroup' ]);
        return new RedirectResponse($url);
    }
}