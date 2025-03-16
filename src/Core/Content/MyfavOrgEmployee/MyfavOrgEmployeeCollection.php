<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployee;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavOrgEmployeeEntity $entity)
 * @method void                   set(string $key, MyfavOrgEmployeeEntity $entity)
 * @method MyfavOrgEmployeeEntity[]    getIterator()
 * @method MyfavOrgEmployeeEntity[]    getElements()
 * @method MyfavOrgEmployeeEntity|null get(string $key)
 * @method MyfavOrgEmployeeEntity|null first()
 * @method MyfavOrgEmployeeEntity|null last()
 */
class MyfavOrgEmployeeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavOrgEmployeeEntity::class;
    }
}
