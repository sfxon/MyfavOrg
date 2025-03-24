<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerEmailUnique;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerPasswordMatches;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerService
{
    public function __construct(
        private readonly EntityRepository $customerRepository,)
    {
    }

    /**
     * loadCustomerById
     *
     * @param  Context $context
     * @param  string $customerId
     * @return mixed
     */
    public function loadCustomerById(Context $context, string $customerId): mixed
    {
        $criteria = new Criteria([$customerId]);
        $customer = $this->customerRepository->search($criteria, $context)->first();
        return $customer;
    }

    /**
     * setLastLoginDate
     *
     * @param  Context $context
     * @param  string $customerId
     * @return void
     */
    public function setLastLoginDate(Context $context, string $customerId): void
    {
        $this->customerRepository->update([
            [
                'id' => $customerId,
                'lastLogin' => new \DateTimeImmutable(),
            ],
        ], $context);
    }
}