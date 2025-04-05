<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgCompanyCustomerGroup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgCompanyCustomerGroupEntity $entity)
 * @method void                   set(string $key, MyfavOrgCompanyCustomerGroupEntity $entity)
 * @method MyfavOrgCompanyCustomerGroupEntity[]    getIterator()
 * @method MyfavOrgCompanyCustomerGroupEntity[]    getElements()
 * @method MyfavOrgCompanyCustomerGroupEntity|null get(string $key)
 * @method MyfavOrgCompanyCustomerGroupEntity|null first()
 * @method MyfavOrgCompanyCustomerGroupEntity|null last()
 */
class MyfavOrgCompanyCustomerGroupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgCompanyCustomerGroupEntity::class;
    }
}
