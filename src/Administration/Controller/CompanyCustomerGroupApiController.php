<?php declare(strict_types=1);

namespace Myfav\Org\Administration\Controller;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Shopware\Core\Content\MailTemplate\Subscriber\MailSendSubscriberConfig;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\FetchModeHelper;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route(defaults: ['_routeScope' => ['api']])]
class CompanyCustomerGroupApiController extends AbstractController
{
    public function __construct(
        protected readonly EntityRepository $myfavOrgCompanyCustomerGroupRepository,)
    {
    }

    #[Route(path: '/api/myfav/org/company/customer/group/update', name: 'api.action.myfav.org.company.customer.group.update', methods: ['POST'])]
    public function update(Context $context, Request $request): JsonResponse
    {
        $requestValues = $request->request->all();

        $myfavOrgCompanyId = $requestValues['myfavOrgCompanyId'];
        $customerGroups = $requestValues['customerGroups'];

        // Remove all myfavOrgCompanyId entries for this company.
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgCompanyId', $myfavOrgCompanyId));
        $ids = $this->myfavOrgCompanyCustomerGroupRepository->searchIds($criteria, $context)->getIds();

        foreach($ids as $id) {
            $this->myfavOrgCompanyCustomerGroupRepository->delete([
                [
                    'id' => $id
                ]
            ], $context);
        }

        // Add all customerGroups that should be added.
        $insertDataArray = [];

        foreach($customerGroups as $customerGroupId) {
            $insertDataArray[] = [
                'myfavOrgCompanyId' => $myfavOrgCompanyId,
                'customerGroupId' => $customerGroupId
            ];
        }

        $this->myfavOrgCompanyCustomerGroupRepository->create($insertDataArray, $context);

        return new JsonResponse(['status' => 'success']);
    }
}