<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployee;

use Myfav\Org\Core\Content\MyfavOrgAclRole\MyfavOrgAclRoleDefinition;
use Myfav\Org\Core\Content\MyfavOrgEmployeeAclAttribute\MyfavOrgEmployeeAclAttributeDefinition;
use Myfav\Org\Core\Content\OrderClearanceGroup\OrderClearanceGroupDefinition;
use Myfav\Org\Core\Content\OrderClearanceRole\OrderClearanceRoleDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Checkout\Payment\PaymentMethodDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\NoConstraint;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\EmailField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;
use Shopware\Core\System\Salutation\SalutationDefinition;

class MyfavOrgEmployeeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'myfav_org_employee';

    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return MyfavOrgEmployeeEntity::class;
    }

    /**
     * getCollectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return MyfavOrgEmployeeCollection::class;
    }

    /**
     * defineFields
     *
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey(), new ApiAware()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('myfav_org_acl_role_id', 'myfavOrgAclRoleId', CustomerGroupDefinition::class))->addFlags(new ApiAware()),
            (new FkField('default_payment_method_id', 'defaultPaymentMethodId', PaymentMethodDefinition::class))->addFlags(new ApiAware()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new ApiAware()),
            (new FkField('default_billing_address_id', 'defaultBillingAddressId', CustomerAddressDefinition::class))->addFlags(new ApiAware(), new NoConstraint()),
            (new FkField('default_shipping_address_id', 'defaultShippingAddressId', CustomerAddressDefinition::class))->addFlags(new ApiAware(), new NoConstraint()),
            (new FkField('salutation_id', 'salutationId', SalutationDefinition::class))->addFlags(new ApiAware()),
            (new StringField('first_name', 'firstName'))->addFlags(new ApiAware(), new Required()),
            (new StringField('last_name', 'lastName'))->addFlags(new ApiAware(), new Required()),
            (new StringField('position', 'position'))->addFlags(new ApiAware()),
            (new PasswordField('password', 'password', \PASSWORD_DEFAULT, [], PasswordField::FOR_CUSTOMER))->removeFlag(ApiAware::class),
            (new EmailField('email', 'email'))->addFlags(new ApiAware(), new Required()),
            (new StringField('title', 'title'))->addFlags(new ApiAware()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),
            (new FkField('order_clearance_group_id', 'orderClearanceGroupId', OrderClearanceGroupDefinition::class))->addFlags(new ApiAware()),
            (new FkField('order_clearance_role_id', 'orderClearanceRoleId', OrderClearanceRoleDefinition::class))->addFlags(new ApiAware()),
            (new DateTimeField('first_login', 'firstLogin'))->addFlags(new ApiAware()),
            (new DateTimeField('last_login', 'lastLogin'))->addFlags(new ApiAware()),
            (new CustomFields())->addFlags(new ApiAware()),

            (new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('myfavOrgAclRole', 'myfav_org_acl_role_id', MyfavOrgAclRoleDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('defaultPaymentMethod', 'default_payment_method_id', PaymentMethodDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('defaultBillingAddress', 'default_billing_address_id', CustomerAddressDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('activeBillingAddress', 'active_billing_address_id', CustomerAddressDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('defaultShippingAddress', 'default_shipping_address_id', CustomerAddressDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('activeShippingAddress', 'active_shipping_address_id', CustomerAddressDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('salutation', 'salutation_id', SalutationDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new OneToManyAssociationField('addresses', CustomerAddressDefinition::class, 'customer_id', 'id'))->addFlags(new ApiAware()),
            (new OneToManyAssociationField('myfavOrgEmployeeAclAttributes', MyfavOrgEmployeeAclAttributeDefinition::class, 'myfav_org_employee_id')),
            (new ManyToOneAssociationField('orderClearanceGroup', 'order_clearance_group_id', OrderClearanceGroupDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('orderClearanceRole', 'order_clearance_role_id', OrderClearanceRoleDefinition::class, 'id', false))->addFlags(new ApiAware()),
        ]);
    }
}