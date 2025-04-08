<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Service\AccessRightsService;
use Myfav\Org\Service\AclAttributeGroupService;
use Myfav\Org\Service\AclRoleService;
use Myfav\Org\Service\AddressService;
use Myfav\Org\Service\CountryService;
use Myfav\Org\Service\CustomerService;
use Myfav\Org\Service\LanguageService;
use Myfav\Org\Service\MyfavSalesChannelContextService;
use Myfav\Org\Service\SalutationService;
use Myfav\Org\Storefront\Page\Employee\EmployeePageLoader;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        private readonly CustomerService $customerService,
        private readonly EmployeePageLoader $employeePageLoader,
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

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Create entry.
        $dataBag = new DataBag($requestDataBag->all());
        $dataBag->add(['salesChannelId' => $salesChannelContext->getSalesChannelId()]);
        $result = $this->customerService->createCustomerFromRequest($context, $dataBag, $salesChannelContext);

        if($result['hasErrors']) {
            return $this->showNewPage($context, $request, $salesChannelContext, $result['errors'], $dataBag);
        }

        $url = $this->router->generate('myfav.org.employee.list', [ 'successMessage'=> 'createdEmployee' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/employee/list', name: 'myfav.org.employee.list', options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET', 'POST'])]
    public function listEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.read', 'frontend.account.home.page');

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Set Pagination data and search query.
        $currentPage = (int) $request->query->get('p', 1);
        $limit = 25;

        $searchQuery = $request->query->get('searchQuery', null);

        if(null !== $searchQuery) {
            $searchQuery = trim($searchQuery);

            if(strlen($searchQuery) === 0) {
                $searchQuery = null;
            }
        }

        // Generate page.
        $page = $this->employeePageLoader->load($request, $salesChannelContext);

        if($company === null) {
            throw new \Exception('Company not found.');
        }

        // Query employees.
        $employees = $this->customerService->loadList($context, $company->getId(), $currentPage, $limit, $searchQuery);

        // Render storefront.
        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/index.html.twig', [
            'currentPage' => $currentPage,
            'employees' => $employees,
            'limit' => $limit,
            'successMessage' => $request->query->get('successMessage'),
            'page' => $page,
            'searchQuery' => $searchQuery,
            'userAclCanCreate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.create'),
            'userAclCanUpdate' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.update'),
            'userAclCanDelete' => $this->accessRightsService->hasRight($salesChannelContext, 'employee.delete'),
        ]);
    }

    #[Route(path: '/myfav/org/employee/delete', name: 'myfav.org.employee.delete',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function deleteEmployee(Request $request, Context $context, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.delete', 'myfav.org.employee.list');
        
        // Load customerId
        $customerId = $request->query->get('customerId');

        if($customerId === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        $customer = $this->customerService->loadCustomerById($context, $customerId, $company->getId());

        if($customer === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Check, that this customer is not the logged in customer.
        // The logged in customer cannot delete it's own account.
        $loggedInCustomer = $this->myfavSalesChannelContextService->getCustomer($salesChannelContext);

        if($loggedInCustomer->getId() === $customer->getId()) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        $this->customerService->deleteCustomer($context, $customerId);

        $url = $this->router->generate('myfav.org.employee.list', [ 'successMessage'=> 'deletedEmployee' ]);
        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/org/employee/edit', name: 'myfav.org.employee.edit',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function editEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.update', 'myfav.org.employee.list');

        // Load customerId
        $customerId = $request->query->get('customerId');

        if($customerId === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        $customer = $this->customerService->loadCustomerById($context, $customerId, $company->getId());

        if($customer === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        $useAlternativeShippingAddress = false;

        if($customer->getDefaultBillingAddressId() !== $customer->getDefaultShippingAddressid()) {
            $useAlternativeShippingAddress = true;
        }

        // Show edit page.
        return $this->showEditPage($context, $request, $salesChannelContext, [], $customer, $company, $useAlternativeShippingAddress);
    }

    #[Route(path: '/myfav/org/employee/new', name: 'myfav.org.employee.new',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['GET'])]
    public function newEmployee(Context $context, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.create', 'myfav.org.employee.list');

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        return $this->showNewPage($context, $request, $salesChannelContext);
    }

    #[Route(path: '/myfav/org/employee/update', name: 'myfav.org.employee.update',  options: ['seo' => false], defaults: ['_loginRequired' => true], methods: ['POST'])]
    public function updateEmployee(Context $context, Request $request, RequestDataBag $requestDataBag, SalesChannelContext $salesChannelContext): Response
    {
        $this->accessRightsService->validate($salesChannelContext, 'employee.update', 'myfav.org.employee.list');

        // Load customerId
        $customerId = $request->query->get('customerId');

        if($customerId === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Load customer.
        $customer = $this->customerService->loadCustomerById($context, $customerId, $company->getId());

        if($customer === null) {
            $url = $this->router->generate('myfav.org.employee.list');
            return new RedirectResponse($url);
        }

        // Get request data.
        $dataBag = new DataBag($requestDataBag->all());
        $customerId = $request->query->get('customerId');
        $result = $this->customerService->updateCustomerFromRequest($context, $dataBag, $salesChannelContext, $customer, $company);

        $useAlternativeShippingAddress = false;

        if($dataBag->get('useAlternativeShippingAddress')) {
            $useAlternativeShippingAddress = true;
        }

        if($result['hasErrors']) {
            return $this->showEditPage($context, $request, $salesChannelContext, $result['errors'], $customer, $company, $useAlternativeShippingAddress);
        }

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
    private function showEditPage(
        Context $context,
        Request $request,
        SalesChannelContext $salesChannelContext,
        $errors = null,
        mixed $customer,
        mixed $company,
        bool $useAlternativeShippingAddress): Response
    {
        $page = $this->employeePageLoader->load($request, $salesChannelContext);

        // Check, if the user that is to be edited is the current user.
        $loggedInCustomer = $this->myfavSalesChannelContextService->getCustomer($salesChannelContext);
        $currentEditUserIsLoggedInUser = false;

        if($loggedInCustomer->getId() === $customer->getId()) {
            $currentEditUserIsLoggedInUser = true;
        }

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/edit.html.twig', [
            'aclRoles' => $this->aclRoleService->loadList($context),
            'addresses' => $this->addressService->loadList($context, $salesChannelContext->getCustomer()->getId()),
            'countries' => $this->countryService->loadSalesChannelCountries($salesChannelContext),
            'company' => $company,
            'currentEditUserIsLoggedInUser' => $currentEditUserIsLoggedInUser,
            'customer' => $customer,
            'editMode' => 'edit',
            'errors' => $errors,
            'languages' => $this->languageService->loadList($context),
            'page' => $page,
            'salutations' => $this->salutationService->loadList($context),
            'useAlternativeShippingAddress' => $useAlternativeShippingAddress,
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
    private function showNewPage(Context $context, Request $request, SalesChannelContext $salesChannelContext, $errors = [], $customerData = null): Response
    {
        $page = $this->employeePageLoader->load($request, $salesChannelContext);

        if($customerData === null) {
            $customerData = new DataBag();
        }

        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company === null) {
            throw new \Exception('Company not found.');
        }

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/new.html.twig', [
            'aclRoles' => $this->aclRoleService->loadList($context),
            'countries' => $this->countryService->loadSalesChannelCountries($salesChannelContext),
            'company' => $company,
            'editMode' => 'new',
            'customer' => $customerData->all(),
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