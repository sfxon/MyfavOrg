<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Service\AccessRightsService;
use Myfav\Org\Service\AclAttributeGroupService;
use Myfav\Org\Service\AclRoleAttributeService;
use Myfav\Org\Service\AclRoleService;
use Myfav\Org\Storefront\Page\AclRole\AclRolePageLoader;
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
class AclRoleController extends StorefrontController
{
    /**
     * __construct
     */
    public function __construct(
        private readonly AccessRightsService $accessRightsService,
        private readonly AclAttributeGroupService $aclAttributeGroupService,
        private readonly AclRoleAttributeService $aclRoleAttributeService,
        private readonly AclRolePageLoader $aclRolePageLoader,
        private readonly AclRoleService $aclRoleService,
        private readonly RouterInterface $router,
    )
    {
    }

    #[Route(path: '/myfav/org/acl-role/create', name: 'myfav.org.aclrole.create', methods: ['POST'], defaults: ['XmlHttpRequest' => 'true'])]
    public function createRole(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.create', 'myfav.org.aclrole.list');
        $aclRoleId = $this->aclRoleService->createRole($context, $request->request->get('name'));
        $this->aclRoleAttributeService->updateRoleAttributes(
            $context,
            $aclRoleId,
            $request->request->all()['aclAttributes'] ?? []
        );

        $url = $this->router->generate('myfav.org.aclrole.list', [ 'successMessage'=> 'createdRole' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/acl-role/list', name: 'myfav.org.aclrole.list', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function listRole(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.read', 'frontend.account.home.page');
        $page = $this->aclRolePageLoader->load($request, $salesChannelContext);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/acl-role/index.html.twig', [
            'aclRoles' => $this->aclRoleService->loadList($context),
            'successMessage' => $request->query->get('successMessage'),
            'page' => $page,
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'role.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/acl-role/delete', name: 'myfav.org.aclrole.delete', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function deleteRole(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.delete', 'myfav.org.aclrole.list');
        $aclRoleId = $request->query->get('aclRoleId');
        // You might want to get the role first: $this->aclRoleService->loadRole($context, $request->request->get('name'));

        $this->aclRoleAttributeService->removeAllAttributesFromRole(
            $context,
            $aclRoleId
        );
        $this->aclRoleService->deleteRole($context, $aclRoleId);

        $url = $this->router->generate('myfav.org.aclrole.list', [ 'successMessage'=> 'deletedRole' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/acl-role/edit', name: 'myfav.org.aclrole.edit', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function editRole(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.update', 'myfav.org.aclrole.list');
        $page = $this->aclRolePageLoader->load($request, $salesChannelContext);
        $aclRoleId = $request->query->get('aclRoleId');
        $aclRole = $this->aclRoleService->loadRole($context, $aclRoleId);
        $activatedRoleAttributes = $aclRole->getAttributesIndexByAttributeId();
        $aclAttributeGroups = $this->aclAttributeGroupService->loadAll($context);

        // Todo: Security check. Check if this role belongs to this account!

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/acl-role/edit.html.twig', [
            'aclAttributeGroups' => $aclAttributeGroups,
            'page' => $page,
            'aclRole' => $aclRole,
            'activatedRoleAttributes' => $activatedRoleAttributes,
            'editMode' => 'edit',
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'role.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/acl-role/new', name: 'myfav.org.aclrole.new', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function newRole(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.create', 'frontend.account.home.page');
        $page = $this->aclRolePageLoader->load($request, $salesChannelContext);
        $aclAttributeGroups = $this->aclAttributeGroupService->loadAll($context);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/acl-role/edit.html.twig', [
            'aclAttributeGroups' => $aclAttributeGroups,
            'page' => $page,
            'editMode' => 'new',
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'role.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'role.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/acl-role/update', name: 'myfav.org.aclrole.update', methods: ['POST'], defaults: ['XmlHttpRequest' => 'true'])]
    public function updateRole(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'role.update', 'myfav.org.aclrole.list');
        $aclRoleId = $request->query->get('aclRoleId');
        $this->aclRoleService->updateRole($context, $aclRoleId, $request->request->get('name'));
        $this->aclRoleAttributeService->updateRoleAttributes(
            $context,
            $aclRoleId,
            $request->request->all()['aclAttributes'] ?? []
        );

        $url = $this->router->generate('myfav.org.aclrole.list', [ 'successMessage'=> 'updatedRole' ]);
        return new RedirectResponse($url);
    }
}