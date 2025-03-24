<?php

namespace Myfav\Org;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;

/**
 * MyfavImp
 */
class MyfavOrg extends Plugin
{
        /**
     * install
     *
     * @param  InstallContext $installContext
     * @return void
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
        $this->installCustomFields($installContext->getContext());
    }

    /**
     * uninstall
     *
     * @param  UninstallContext $context
     * @return void
     */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }
    }

    /**
     * update
     *
     * @param  UpdateContext $updateContext
     * @return void
     */
    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);
    }

    /**
     * installCustomFields
     *
     * @param  Context $context
     * @return void
     */
    private function installCustomFields(Context $context): void
    {
        $fieldSetId = '0195c86efafd72c69b6416350993573e';
        $myfavOrgEmployeeIdFieldId = '0195c86f33ee7382bd93a372152b42c8';
        $myfavOrgEmployeeCustomerIdFieldId = '0195c8718ea070bba2b7dd75d86e1047';
        $myfavOrgEmployeeSalutationIdFieldId = '0195c871a7467289ab1d28bf4c3091eb';
        $myfavOrgEmployeeSalutationFieldId = '0195c871bf5872dcaee6afa32afcd674';
        $myfavOrgEmployeeFirstnameFieldId = '0195c871d1497385bad4c1af78665551';
        $myfavOrgEmployeeLastnameFieldId = '0195c871e1307172ba235680ab3a3a29';
        $myfavOrgEmployeePositionFieldId = '0195c872bcc073eaa589a485d15e7130';
        $myfavOrgEmployeeEmailFieldId = '0195c872d80470bb8745581c64021d96';
        $myfavOrgEmployeeTitleFieldId = '0195c872eae873d3a063a22444f522de';

        $check = $this->customFieldSetExists($context, $fieldSetId);

        if(null === $check) {
            new \Exception('EntitySearchResult for field set search should not be null.');
        }

        $customFieldSetRepository = $this->container->get('custom_field_set.repository');
        $customFieldSetRepository->upsert([
            [
                'id' => $fieldSetId,
                'name' => 'myfav_org_order_data',
                'config' => [
                    'label' => [
                        'de-DE' => 'Organisation'
                    ]
                ],
                'customFields' => [
                    // employeeId
                    [
                        'id' => $myfavOrgEmployeeIdFieldId,
                        'name' => 'myfav_org_order_data_employee_id',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'employeeId',
                                'de-DE' => 'employeeId'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 10,
                        ],
                        'active' => true
                    ],
                    // customerId
                    [
                        'id' => $myfavOrgEmployeeCustomerIdFieldId,
                        'name' => 'myfav_org_order_data_customer_id',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'customerId',
                                'de-DE' => 'customerId'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 20,
                        ],
                        'active' => true
                    ],
                    // salutationId
                    [
                        'id' => $myfavOrgEmployeeSalutationIdFieldId,
                        'name' => 'myfav_org_order_data_salutation_id',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'salutationId',
                                'de-DE' => 'salutationId'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 30,
                        ],
                        'active' => true
                    ],
                    // salutation
                    [
                        'id' => $myfavOrgEmployeeSalutationFieldId,
                        'name' => 'myfav_org_order_data_salutation',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Salutation',
                                'de-DE' => 'Anrede'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 40,
                        ],
                        'active' => true
                    ],
                    // firstname
                    [
                        'id' => $myfavOrgEmployeeFirstnameFieldId,
                        'name' => 'myfav_org_order_data_firstname',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Firstname',
                                'de-DE' => 'Vorname'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 50,
                        ],
                        'active' => true
                    ],
                    // lastname
                    [
                        'id' => $myfavOrgEmployeeLastnameFieldId,
                        'name' => 'myfav_org_order_data_lastname',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Lastname',
                                'de-DE' => 'Nachname'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 60,
                        ],
                        'active' => true
                    ],
                    // position
                    [
                        'id' => $myfavOrgEmployeePositionFieldId,
                        'name' => 'myfav_org_order_data_position',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Position',
                                'de-DE' => 'Position'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 70,
                        ],
                        'active' => true
                    ],
                    // email
                    [
                        'id' => $myfavOrgEmployeeEmailFieldId,
                        'name' => 'myfav_org_order_data_email',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Email',
                                'de-DE' => 'E-Mail'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 80,
                        ],
                        'active' => true
                    ],
                    // title
                    [
                        'id' => $myfavOrgEmployeeTitleFieldId,
                        'name' => 'myfav_org_order_data_title',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Title',
                                'de-DE' => 'Titel'
                            ],
                            'type' => 'text',
                            'componentName' => 'sw-text-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 90,
                        ],
                        'active' => true
                    ],
                ],
                'relations' => [
                    [
                        'id' => $fieldSetId,
                        'entityName' => 'order'
                    ]
                ]
            ]
        ], $context);
    }

    /**
     * Check if the customFieldSet already exists.
     *
     * @param  Context $context
     * @param  string $fieldSetId
     * @return IdSearchResult
     */
    private function customFieldSetExists(Context $context, string $fieldSetId): ?IdSearchResult
    {
        return $this->container->get('custom_field_set.repository')->searchIds(
            (new Criteria())->addFilter(new EqualsFilter('id', $fieldSetId)),
            $context
        );
    }
}