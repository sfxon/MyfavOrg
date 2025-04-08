<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;

class AclRoleService
{
    public function __construct(private readonly EntityRepository $myfavAclRoleRepository)
    {
    }

    /**
     * createRole
     *
     * @param  Context $context
     * @param  string $name
     * @param  string $companyId
     */
    public function createRole(Context $context, string $name, string $companyId): string
    {
        $id = Uuid::randomHex();
        $this->myfavAclRoleRepository->create([
            [
                'id' => $id,
                'name' => $name,
                'myfavOrgCompanyId' => $companyId
            ]
        ], $context);

        return $id;
    }

    /**
     * deleteRole
     *
     * @param  mixed $context
     * @param  mixed $aclRoleId
     * @return void
     */
    public function deleteRole(Context $context, string $aclRoleId): void
    {
        $this->myfavAclRoleRepository->delete([
            [
                'id' => $aclRoleId
            ]
        ], $context);
    }

    /**
     * loadRole
     *
     * @param  Context $context
     * @param  string $aclRoleId
     * @param  string $companyId
     * @return mixed
     */
    public function loadRole(Context $context, string $aclRoleId, string $companyId): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $aclRoleId));
        $criteria->addFilter(new EqualsFilter('myfavOrgCompanyId', $companyId));
        $criteria->addAssociation('myfavOrgAclRoleAttributes');
        $aclRoles = $this->myfavAclRoleRepository->search($criteria, $context)->first();
        return $aclRoles;
    }

    /**
     * loadList
     *
     * @param  Context $context
     * @param  string $companyId
     * @return EntitySearchResult
     */
    public function loadList(Context $context, string $companyId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgCompanyId', $companyId));
        $criteria->addSorting(new FieldSorting('name', FieldSorting::ASCENDING));
        $aclRoles = $this->myfavAclRoleRepository->search($criteria, $context);
        return $aclRoles;
    }

    /**
     * updateRole
     *
     * @param  Context $context
     * @param  string $id
     * @param  string $name
     */
    public function updateRole(Context $context, string $id, string $name,): string
    {
        $this->myfavAclRoleRepository->update([
            [
                'id' => $id,
                'name' => $name,
            ]
        ], $context);

        return $id;
    }
}