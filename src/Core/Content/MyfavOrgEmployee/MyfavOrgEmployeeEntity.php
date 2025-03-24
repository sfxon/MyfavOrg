<?php declare(strict_types=1);

namespace Myfav\Org\Core\Content\MyfavOrgEmployee;

use Myfav\Org\Core\Content\MyfavOrgAclRole\MyfavOrgAclRoleEntity;
use Myfav\Org\Core\Content\MyfavOrgEmployeeAclAttribute\MyfavOrgEmployeeAclAttributeCollection;
use Myfav\Org\Core\Content\OrderClearanceGroup\OrderClearanceGroupEntity;
use Myfav\Org\Core\Content\OrderClearanceRole\OrderClearanceRoleEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressEntity;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Salutation\SalutationEntity;

class MyfavOrgEmployeeEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    protected ?string $customerId;
    protected ?string $myfavOrgAclRoleId;
    protected ?string $defaultPaymentMethodId;
    protected ?string $languageId;
    protected ?string $defaultBillingAddressId;
    protected ?string $defaultShippingAddressId;
    protected ?string $salutationId;
    protected ?string $firstName;
    protected ?string $lastName;
    protected ?string $position;
    protected ?string $password;
    protected ?string $email;
    protected ?string $title;
    protected bool $active;
    protected ?string $orderClearanceGroupId;
    protected ?string $orderClearanceRoleId;
    protected ?\DateTimeInterface $firstLogin = null;
    protected ?\DateTimeInterface $lastLogin = null;

    protected ?CustomerEntity $customer;
    protected ?MyfavOrgAclRoleEntity $myfavOrgAclRole;
    protected ?PaymentMethodEntity $defaultPaymentMethod;
    protected ?LanguageEntity $language;
    protected ?CustomerAddressEntity $defaultBillingAddress;
    protected ?CustomerAddressEntity $defaultShippingAddress;
    protected ?SalutationEntity $salutation;
    protected ?MyfavOrgEmployeeAclAttributeCollection $myfavOrgEmployeeAclAttributes;
    protected ?OrderClearanceGroupEntity $orderClearanceGroup;
    protected ?OrderClearanceRoleEntity $orderClearanceRole;

    // $customerId
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }

    // $myfavOrgAclRoleId
    public function getMyfavOrgAclRoleId(): ?string
    {
        return $this->myfavOrgAclRoleId;
    }

    public function setMyfavOrgAclRoleId(?string $myfavOrgAclRoleId): void
    {
        $this->myfavOrgAclRoleId = $myfavOrgAclRoleId;
    }

    // $defaultPaymentMethodId
    public function getDefaultPaymentMethodId(): ?string
    {
        return $this->defaultPaymentMethodId;
    }

    public function setDefaultPaymentMethodId(?string $defaultPaymentMethodId): void
    {
        $this->defaultPaymentMethodId = $defaultPaymentMethodId;
    }

    // $languageId
    public function getLanguageId(): ?string
    {
        return $this->languageId;
    }

    public function setLanguageId(?string $languageId): void
    {
        $this->languageId = $languageId;
    }

    // $defaultBillingAddressId
    public function getDefaultBillingAddressId(): ?string
    {
        return $this->defaultBillingAddressId;
    }

    public function setDefaultBillingAddressId(?string $defaultBillingAddressId): void
    {
        $this->defaultBillingAddressId = $defaultBillingAddressId;
    }

    // $defaultShippingAddressId
    public function getDefaultShippingAddressId(): ?string
    {
        return $this->defaultShippingAddressId;
    }

    public function setDefaultShippingAddressId(?string $defaultShippingAddressId): void
    {
        $this->defaultShippingAddressId = $defaultShippingAddressId;
    }

    // $salutationId
    public function getSalutationId(): ?string
    {
        return $this->salutationId;
    }

    public function setSalutationId(?string $salutationId): void
    {
        $this->salutationId = $salutationId;
    }

    // $firstName
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    // $lastName
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    // $position
    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    // $password
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    // $email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    // $title
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    // $active
    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    // $orderClearanceGroupId
    public function getOrderClearanceGroupId(): ?string
    {
        return $this->orderClearanceGroupId;
    }

    public function setOrderClearanceGroupId(?string $orderClearanceGroupId): void
    {
        $this->orderClearanceGroupId = $orderClearanceGroupId;
    }

    // $orderClearanceRoleId
    public function getOrderClearanceRoleId(): ?string
    {
        return $this->orderClearanceRoleId;
    }

    public function setOrderClearanceRoleId(?string $orderClearanceRoleId): void
    {
        $this->orderClearanceRoleId = $orderClearanceRoleId;
    }

    // $firstLogin
    public function getFirstLogin(): ?\DateTimeInterface
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(?\DateTimeInterface $firstLogin): void
    {
        $this->firstLogin = $firstLogin;
    }

    // $lastLogin
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    // $customer
    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }

    // $myfavOrgAclRole
    public function getMyfavOrgAclRole(): ?MyfavOrgAclRoleEntity
    {
        return $this->myfavOrgAclRole;
    }

    public function setMyfavOrgAclRole(?MyfavOrgAclRoleEntity $myfavOrgAclRole): void
    {
        $this->myfavOrgAclRole = $myfavOrgAclRole;
    }

    // $defaultPaymentMethod
    public function getDefaultPaymentMethod(): ?PaymentMethodEntity
    {
        return $this->defaultPaymentMethod;
    }

    public function setDefaultPaymentMethod(?PaymentMethodEntity $defaultPaymentMethod): void
    {
        $this->defaultPaymentMethod = $defaultPaymentMethod;
    }

    // $language
    public function getLanguage(): ?LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEntity $language): void
    {
        $this->language = $language;
    }

    // $defaultBillingAddress
    public function getDefaultBillingAddress(): ?CustomerAddressEntity
    {
        return $this->defaultBillingAddress;
    }

    public function setDefaultBillingAddress(?CustomerAddressEntity $defaultBillingAddress): void
    {
        $this->defaultBillingAddress = $defaultBillingAddress;
    }

    // $defaultShippingAddress
    public function getDefaultShippingAddress(): ?CustomerAddressEntity
    {
        return $this->defaultShippingAddress;
    }

    public function setDefaultShippingAddress(?CustomerAddressEntity $defaultShippingAddress): void
    {
        $this->defaultShippingAddress = $defaultShippingAddress;
    }

    // $salutation
    public function getSalutation(): ?SalutationEntity
    {
        return $this->salutation;
    }

    public function setSalutation(?SalutationEntity $salutation): void
    {
        $this->salutation = $salutation;
    }

    // myfavOrgEmployeeAclAttributes
    public function getMyfavOrgEmployeeAclAttributes(): ?MyfavOrgEmployeeAclAttributeCollection
    {
        return $this->myfavOrgEmployeeAclAttributes;
    }

    public function setMyfavOrgEmployeeAclAttributes(?MyfavOrgEmployeeAclAttributeCollection $myfavOrgEmployeeAclAttributes): void
    {
        $this->myfavOrgEmployeeAclAttributes = $myfavOrgEmployeeAclAttributes;
    }

    public function getAttributesIndexByAttributeId(): array
    {
        $retval = [];

        if($this->myfavOrgEmployeeAclAttributes === null) {
            return $retval;
        }

        foreach($this->myfavOrgEmployeeAclAttributes as $aclAttribute) {
            $retval[$aclAttribute->getMyfavOrgAclAttributeId()] = $aclAttribute;
        }

        return $retval;
    }

    public function getAttributesIndexByTechnicalName(): array
    {
        $retval = [];

        if($this->myfavOrgEmployeeAclAttributes === null) {
            return $retval;
        }

        foreach($this->myfavOrgEmployeeAclAttributes as $aclAttribute) {
            $retval[$aclAttribute->getMyfavOrgAclAttribute()->getTechnicalName()] = $aclAttribute;
        }

        return $retval;
    }

    // $orderClearanceGroup
    public function getOrderClearanceGroup(): ?OrderClearanceGroupEntity
    {
        return $this->orderClearanceGroup;
    }

    public function setOrderClearanceGroup(?OrderClearanceGroupEntity $orderClearanceGroup): void
    {
        $this->orderClearanceGroup = $orderClearanceGroup;
    }

    // $orderClearanceRole
    public function getOrderClearanceRole(): ?OrderClearanceRoleEntity
    {
        return $this->orderClearanceRole;
    }

    public function setOrderClearanceRole(?OrderClearanceRoleEntity $orderClearanceRole): void
    {
        $this->orderClearanceRole = $orderClearanceRole;
    }
}
