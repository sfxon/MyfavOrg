<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Controller;

use Myfav\Org\Storefront\Page\Employee\EmployeePageLoader;
use Shopware\Core\Content\Media\Pathname\PathnameStrategy\PathnameStrategyInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\RepositoryIterator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class EmployeeController extends StorefrontController
{
    private $separator = ',';
    private $enclosure = '"';
    private $escaper = '\\';

    /**
     * __construct
     */
    public function __construct(
        private readonly EmployeePageLoader $employeePageLoader,
    )
    {
    }

    #[Route(path: '/myfav/org/employee/list', name: 'myfav.org.employee.list', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function listAccountOrg(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $page = $this->employeePageLoader->load($request, $salesChannelContext);

        return $this->renderStorefront('@MyfavOrg/storefront/page/myfav/org/employee/index.html.twig', [
            //'example' => 'Hello world',
            'page' => $page
        ]);
    }
}