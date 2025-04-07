<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Service\AccessRightsService;
use Myfav\Org\Service\AclAttributeGroupService;
use Myfav\Org\Service\AclRoleService;
use Myfav\Org\Service\AddressService;
use Myfav\Org\Service\CountryService;
use Myfav\Org\Service\EmployeeAclAttributeService;
use Myfav\Org\Service\EmployeeService;
use Myfav\Org\Service\LanguageService;
use Myfav\Org\Service\MyfavSalesChannelContextService;
use Myfav\Org\Service\SalutationService;
use Myfav\Org\Storefront\Page\Employee\EmployeePageLoader;
use Shopware\Core\Content\Media\Pathname\PathnameStrategy\PathnameStrategyInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\RepositoryIterator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class EmployeeController extends StorefrontController
{
    /**
     * __construct
     */
    public function __construct(
        private readonly AccessRightsService $accessRightsService,
        private readonly AclAttributeGroupService $aclAttributeGroupService,
        private readonly AclRoleService $aclRoleService,
        private readonly AddressService $addressService,
        private readonly CountryService $countryService,
        private readonly EmployeeAclAttributeService $employeeAclAttributeService,
        private readonly EmployeePageLoader $employeePageLoader,
        private readonly EmployeeService $employeeService,
        private readonly LanguageService $languageService,
        private readonly MyfavSalesChannelContextService $myfavSalesChannelContextService,
        private readonly SalutationService $salutationService,
        private readonly RouterInterface $router,
    )
    {
    }

    #[Route(path: '/myfav/org/employee/create', name: 'myfav.org.employee.create', options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['POST'])]
    public function createEmployee(Request $request, Context $context, RequestDataBag $requestDataBag, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.create', 'myfav.org.employee.list');
        $dataBag = new DataBag($requestDataBag->all());
        $dataBag->add(['salesChannelId' => $salesChannelContext->getSalesChannelId()]);
        $result = $this->employeeService->createEmployeeFromRequest($context, $dataBag, $salesChannelContext);

        if($result['hasErrors']) {
            return $this->showNewPage($context, $request, $salesChannelContext, $result['errors'], $dataBag);
        }

        $this->employeeAclAttributeService->updateEmployeAclAttributes(
            $context,
            $result['id'],
            $request->request->all()['aclAttributes'] ?? []
        );

        $url = $this->router->generate('myfav.org.employee.list', [ 'successMessage'=> 'createdEmployee' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/employee/list', name: 'myfav.org.employee.list', options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET', 'POST'])]
    public function listEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.read', 'frontend.account.home.page');

        $currentPage = (int) $request->query->get('p', 1);
        $limit = 1;

        $searchQuery = $request->query->get('searchQuery', null);

        if(null !== $searchQuery) {
            $searchQuery = trim($searchQuery);

            if(strlen($searchQuery) === 0) {
                $searchQuery = null;
            }
        }

        $page = $this->employeePageLoader->load($request, $salesChannelContext);
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            throw new \Exception('Company not found.');
        }

        $employees = $this->employeeService->loadList($context, $company->getId(), $currentPage, $limit, $searchQuery);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/index.html.twig', [
            'currentPage' => $currentPage,
            'employees' => $employees,
            'limit' => $limit,
            'successMessage' => $request->query->get('successMessage'),
            'page' => $page,
            'searchQuery' => $searchQuery,
            'total' => $employees->getTotal(),
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/employee/delete', name: 'myfav.org.employee.delete',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function deleteEmployee(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.delete', 'myfav.org.employee.list');
        $employeeId = $request->query->get('employeeId');

        if($employeeId === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        $this->employeeAclAttributeService->removeAllAttributesFromEmployee(
            $context,
            $employeeId,
        );

        $this->employeeService->deleteEmployee($context, $employeeId);

        $url = $this->router->generate('myfav.org.employee.list', [ 'successMessage'=> 'deletedEmployee' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/employee/edit', name: 'myfav.org.employee.edit',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function editEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.update', 'myfav.org.employee.list');
        $employeeId = $request->query->get('employeeId');

        if($employeeId === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Show edit page.
        return $this->showEditPage($context, $request, $salesChannelContext);
    }

    #[Route(path: '/myfav/org/employee/new', name: 'myfav.org.employee.new',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function newEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.create', 'myfav.org.employee.list');
        return $this->showNewPage($context, $request, $salesChannelContext);
    }

    #[Route(path: '/myfav/org/employee/update', name: 'myfav.org.employee.update',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['POST'])]
    public function updateRole(Context $context, Request $request, RequestDataBag $requestDataBag, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.update', 'myfav.org.employee.list');
        $dataBag = new DataBag($requestDataBag->all());
        $employeeId = $request->query->get('employeeId');
        $result = $this->employeeService->updateEmployeeFromRequest($context, $employeeId, $dataBag, $salesChannelContext);

        if($result['hasErrors']) {
            return $this->showEditPage($context, $request, $salesChannelContext, $result['errors']);
        }

        $this->employeeAclAttributeService->updateEmployeAclAttributes(
            $context,
            $employeeId,
            $request->request->all()['aclAttributes'] ?? []
        );

        $url = $this->router->generate('myfav.org.employee.list', [ 'successMessage'=> 'updatedEmployee' ]);
        return new RedirectResponse($url);
    }

    /**
     * showEditPage
     *
     * @param  Context $context
     * @param  Request $request
     * @param  SalesChannelContext $salesChannelContext
     * @return Response
     */
    private function showEditPage(Context $context, Request $request, SalesChannelContext $salesChannelContext, $errors = null): Response
    {
        // Todo: Check access rights.
        $page = $this->employeePageLoader->load($request, $salesChannelContext);
        $employeeId = $request->query->get('employeeId');
        $employee = $this->employeeService->loadById($context, $employeeId);
        $activatedEmployeeAclAttributes = $employee->getAttributesIndexByAttributeId();
        $aclAttributeGroups = $this->aclAttributeGroupService->loadAll($context);
        
        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/edit.html.twig', [
            'aclAttributeGroups' => $aclAttributeGroups,
            'aclRoles' => $this->aclRoleService->loadList($context),
            'activatedEmployeeAclAttributes' => $activatedEmployeeAclAttributes,
            'addresses' => $this->addressService->loadList($context, $salesChannelContext->getCustomer()->getId()),
            'editMode' => 'edit',
            'employee' => $employee,
            'errors' => $errors,
            'languages' => $this->languageService->loadList($context),
            'page' => $page,
            'salutations' => $this->salutationService->loadList($context),
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.delete'),
        ]);
    }

    /**
     * showNewPage
     *
     * @param  mixed $context
     * @param  mixed $request
     * @param  mixed $salesChannelContext
     * @return Response
     */
    private function showNewPage(Context $context, Request $request, SalesChannelContext $salesChannelContext, $errors = [], $employeeData = null): Response
    {
        $page = $this->employeePageLoader->load($request, $salesChannelContext);
        $aclAttributeGroups = $this->aclAttributeGroupService->loadAll($context);

        if($employeeData === null) {
            $employeeData = new DataBag();
        }

        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            throw new \Exception('Company not found.');
        }

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/edit.html.twig', [
            'aclAttributeGroups' => $aclAttributeGroups,
            'aclRoles' => $this->aclRoleService->loadList($context),
            'countries' => $this->countryService->loadSalesChannelCountries($salesChannelContext),
            'company' => $company,
            'editMode' => 'new',
            'employee' => $employeeData->all(),
            'errors' => $errors,
            'languages' => $this->languageService->loadList($context),
            'page' => $page,
            'salutations' => $this->salutationService->loadList($context),
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.delete'),
        ]);
    }
}