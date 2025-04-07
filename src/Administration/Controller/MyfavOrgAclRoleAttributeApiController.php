<?php declare(strict_types=1);

namespace Myfav\Org\Administration\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route(defaults: ['_routeScope' => ['api']])]
class MyfavOrgAclRoleAttributeApiController extends AbstractController
{
    public function __construct(
        protected readonly EntityRepository $myfavOrgAclRoleAttributeRepository,)
    {
    }

    #[Route(path: '/api/myfav/org/acl/role/attribute/update', name: 'api.action.myfav.org.acl.role.attribute.update', methods: ['POST'])]
    public function update(Context $context, Request $request): JsonResponse
    {
        $requestValues = $request->request->all();
        $myfavOrgAclRoleId = $requestValues['myfavOrgAclRoleId'];
        $activatedAccessRights = $requestValues['activatedAccessRights'];

        // Remove all myfavOrgCompanyId entries for this company.
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgAclRoleId', $myfavOrgAclRoleId));
        $ids = $this->myfavOrgAclRoleAttributeRepository->searchIds($criteria, $context)->getIds();

        foreach($ids as $id) {
            $this->myfavOrgAclRoleAttributeRepository->delete([
                [
                    'id' => $id
                ]
            ], $context);
        }

        // Add all customerGroups that should be added.
        $insertDataArray = [];

        foreach($activatedAccessRights as $activatedAccessRight) {
            $insertDataArray[] = [
                'myfavOrgAclRoleId' => $myfavOrgAclRoleId,
                'myfavOrgAclAttributeId' => $activatedAccessRight['myfavOrgAclAttributeId']
            ];
        }

        $this->myfavOrgAclRoleAttributeRepository->create($insertDataArray, $context);

        return new JsonResponse(['status' => 'success']);
    }
}