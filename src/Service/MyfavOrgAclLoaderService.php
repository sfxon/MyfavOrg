<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Content\Category\Tree\TreeItem;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class MyfavOrgAclLoaderService
{
    public function __construct(
        private readonly AclRoleAttributeService $aclRoleAttributeService,)
    {
    }

    /**
     * loadEmployeesAclAttributes
     * This function first tries to load employee specific attributes.
     * If it cannot find employee specific attributes, it tries to load employees role-attributes.
     *
     * @param  Context $context
     * @param  string $myfavOrgAclRoleId
     * @return ?EntitySearchResult
     */
    public function loadEmployeesAclAttributes(Context $context, mixed $employee)
    {
        if(null === $employee) {
            return null;
        }

        // Load Attributes by Employee.
        $employeeAclAttributes = $employee->getAttributesIndexByTechnicalName();

        if(count($employeeAclAttributes) > 0) {
            return $employeeAclAttributes;
        }

        // If no attributes where found for the employee, load attributes for employees role.
        $aclRole = $employee->getMyfavOrgAclRole();

        if($aclRole === null) {
            return null;
        }

        $myfavOrgAclRoleAttributes = $aclRole->getAttributesIndexByTechnicalName();

        // If nothing was found, this user has no rights at all. Block!
        if(count($myfavOrgAclRoleAttributes) == 0) {
            return [];
        }

        return $myfavOrgAclRoleAttributes;
    }
}