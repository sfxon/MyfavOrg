<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgCompany;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgCompanyEntity $entity)
 * @method void                   set(string $key, MyfavOrgCompanyEntity $entity)
 * @method MyfavOrgCompanyEntity[]    getIterator()
 * @method MyfavOrgCompanyEntity[]    getElements()
 * @method MyfavOrgCompanyEntity|null get(string $key)
 * @method MyfavOrgCompanyEntity|null first()
 * @method MyfavOrgCompanyEntity|null last()
 */
class MyfavOrgCompanyCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgCompanyEntity::class;
    }
}
