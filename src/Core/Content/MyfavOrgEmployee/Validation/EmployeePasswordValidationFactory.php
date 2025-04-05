<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployee\Validation;

use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class EmployeePasswordValidationFactory implements DataValidationFactoryInterface
{
    /**
     * @internal
     */
    public function __construct(
        /**
         * @todo seems to be the usecase for the shopware api - import or so. maybe rename to CustomerImportValidationService
         */
        //private readonly DataValidationFactoryInterface $employeePasswordValidation
    ) {
    }

    public function create(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition('employee.password.create');

        // $employeePasswordDefinition = $this->employeePasswordValidation->create($context);

        //$this->merge($definition, $employeePasswordDefinition);

       // $this->addConstraints($definition);

        return $definition;
    }

    public function update(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition('employee.password.update');

        //$employeePasswordDefinition = $this->employeePasswordValidation->update($context);

        //$this->merge($definition, $employeePasswordDefinition);

        //$this->addConstraints($definition);

        return $definition;
    }

    /*
    private function addConstraints(DataValidationDefinition $definition): void
    {
    }
    */

    /**
     * merges constraints from the second definition into the first validation definition
     */
    private function merge(DataValidationDefinition $definition, DataValidationDefinition $employeePasswordDefinition): void
    {
        //foreach ($employeePasswordDefinition->getProperties() as $key => $constraints) {
        //    $definition->add($key, ...$constraints);
        //}
    }
}
