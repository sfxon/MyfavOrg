<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;

class AclAttributeGroupService
{
    public function __construct(private readonly EntityRepository $myfavAclAttributeGroupRepository)
    {
    }

    /**
     * loadAll
     *
     * @param  Context $context
     * @return ?EntitySearchResult
     */
    public function loadAll($context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addAssociation('myfavOrgAclAttributes');
        $criteria->addSorting(new FieldSorting('sortOrder', FieldSorting::ASCENDING));
        $aclAttributeGroups = $this->myfavAclAttributeGroupRepository->search($criteria, $context);
        return $aclAttributeGroups;
    }
}
