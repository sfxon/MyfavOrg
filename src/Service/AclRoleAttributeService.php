<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class AclRoleAttributeService
{
    public function __construct(private readonly EntityRepository $myfavAclRoleAttributeRepository)
    {
    }

    /**
     * loadAttributesForRole
     *
     * @param  Context $context
     * @param  string $myfavOrgAclRoleId
     * @return ?EntitySearchResult
     */
    public function loadAttributesForRole(Context $context, string $myfavOrgAclRoleId): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('myfavOrgAclRoleId', $myfavOrgAclRoleId));
        $aclRoleAttributes = $this->myfavAclRoleAttributeRepository->search($criteria, $context);
        return $aclRoleAttributes;
    }

    /**
     * removeAllAttributesFromRole
     *
     * @param  Context $context
     * @param  string $aclRoleId
     * @return void
     */
    public function removeAllAttributesFromRole(Context $context, string $myfavOrgAclRoleId): void
    {
        $attributes = $this->loadAttributesForRole($context, $myfavOrgAclRoleId);

        $attributesToDelete = [];

        foreach($attributes as $attribute) {
            $attributesToDelete[] = [ 'id' => $attribute->getId()];
        }

        if(count($attributesToDelete) === 0) {
            return;
        }

        $this->myfavAclRoleAttributeRepository->delete(
            $attributesToDelete
        , $context);
    }

    /**
     * updateRoleAttributes
     *
     * @param  Context $context
     * @param  string $aclRoleId
     * @param  array $aclAttributes
     * @return void
     */
    public function updateRoleAttributes(Context $context, string $myfavOrgAclRoleId, array $aclAttributes): void
    {
        $this->removeAllAttributesFromRole($context, $myfavOrgAclRoleId);
        
        foreach($aclAttributes as $aclAttributeId => $value) {
            $this->createRoleAttribute($context, $myfavOrgAclRoleId, $aclAttributeId);
        }
    }

    /**
     * createRoleAttribute
     *
     * @param  Context $context
     * @param  string $myfavOrgAclRoleId
     * @param  string $aclAttributeId
     * @return void
     */
    private function createRoleAttribute(Context $context, string $myfavOrgAclRoleId, string $myfavOrgAclAttributeId): void
    {
        $id = Uuid::randomHex();
        $this->myfavAclRoleAttributeRepository->create([
            [
                'id' => $id,
                'myfavOrgAclRoleId' => $myfavOrgAclRoleId,
                'myfavOrgAclAttributeId' => $myfavOrgAclAttributeId,
            ]
        ], $context);
    }
}
