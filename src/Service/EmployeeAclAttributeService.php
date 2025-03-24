<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Content\Category\Tree\TreeItem;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class EmployeeAclAttributeService
{
    public function __construct(private readonly EntityRepository $myfavEmployeeAclAttributeRepository)
    {
    }

    /**
     * loadAttributesForEmployee
     *
     * @param  Context $context
     * @param  string $myfavOrgEmployeeId
     * @return ?EntitySearchResult
     */
    public function loadAttributesForEmployee(Context $context, string $myfavOrgEmployeeId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgEmployeeId', $myfavOrgEmployeeId));
        $employeeAclAttributes = $this->myfavEmployeeAclAttributeRepository->search($criteria, $context);
        return $employeeAclAttributes;
    }

    /**
     * removeAllAttributesFromEmployee
     *
     * @param  Context $context
     * @param  string $myfavOrgEmployeeId
     * @return void
     */
    public function removeAllAttributesFromEmployee(Context $context, string $myfavOrgEmployeeId): void
    {
        $attributes = $this->loadAttributesForEmployee($context, $myfavOrgEmployeeId);

        $attributesToDelete = [];

        foreach($attributes as $attribute) {
            $attributesToDelete[] = [ 'id' => $attribute->getId()];
        }

        if(count($attributesToDelete) === 0) {
            return;
        }

        $this->myfavEmployeeAclAttributeRepository->delete(
            $attributesToDelete
        , $context);
    }

    /**
     * updateEmployeeAttributes
     *
     * @param  Context $context
     * @param  string $myfavOrgEmployeeId
     * @param  array $aclAttributes
     * @return void
     */
    public function updateEmployeAclAttributes(Context $context, string $myfavOrgEmployeeId, array $aclAttributes): void
    {
        $this->removeAllAttributesFromEmployee($context, $myfavOrgEmployeeId);

        foreach($aclAttributes as $aclAttributeId => $value) {
            $this->createEmployeeAclAttribute($context, $myfavOrgEmployeeId, $aclAttributeId);
        }
    }

    /**
     * createEmployeeAttribute
     *
     * @param  Context $context
     * @param  string $myfavOrgEmployeeId
     * @param  string $aclAttributeId
     * @return void
     */
    private function createEmployeeAclAttribute(Context $context, string $myfavOrgEmployeeId, string $myfavOrgAclAttributeId): void
    {
        $id = Uuid::randomHex();
        $this->myfavEmployeeAclAttributeRepository->create([
            [
                'id' => $id,
                'myfavOrgEmployeeId' => $myfavOrgEmployeeId,
                'myfavOrgAclAttributeId' => $myfavOrgAclAttributeId,
            ]
        ], $context);
    }
}
