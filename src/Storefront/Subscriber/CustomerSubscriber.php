<?php declare(strict_types=1);

namespace Myfav\Org\Storefront\Subscriber;

use Myfav\Org\Core\Content\MyfavOrgAclRole\MyfavOrgAclRoleEntity;
use Myfav\Org\Core\Content\MyfavOrgCompany\MyfavOrgCompanyEntity;
use Shopware\Core\Checkout\Customer\CustomerEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityRepository $myfavOrgAclRoleRepository,
        private readonly EntityRepository $myfavOrgCompanyRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerEvents::CUSTOMER_LOADED_EVENT => 'onCustomerLoaded',
        ];
    }

    public function onCustomerLoaded(EntityLoadedEvent $event): void
    {
        // Alle geladenen Customer-Entities abrufen
        $customers = $event->getEntities();
        $context = $event->getContext();

        foreach($customers as $customer) {
            $extensions = $customer->getExtensions();

            if(!isset($extensions['myfavOrgCustomerExtension'])) {
                continue;
            }

            if($extensions['myfavOrgCustomerExtension']['myfavOrgCompanyId'] === null) {
                continue;
            }

            // Load company data.
            $criteria = new Criteria([$extensions['myfavOrgCustomerExtension']['myfavOrgCompanyId']]);
            $criteria->addAssociation('myfavOrgCompanyCustomerGroups');
            $criteria->addAssociation('myfavOrgCompanyCustomerGroups.customerGroup');
            $criteria->addAssociation('myfavOrgCompanyCustomerGroups.customerGroup.translations');
            
            /** @var MyfavOrgCompanyEntity $company */
            $company = $this->myfavOrgCompanyRepository->search($criteria, $context)->first();
            $extensions['myfavOrgCustomerExtension']['myfavOrgCompany'] = $company;

            // Load aclRole with attributes.
            $criteria = new Criteria([$extensions['myfavOrgCustomerExtension']['myfavOrgAclRoleId']]);
            $criteria->addAssociation('myfavOrgAclRoleAttributes');
            $criteria->addAssociation('myfavOrgAclRoleAttributes.myfavOrgAclAttribute');

            /** @var MyfavOrgAclRoleEntity aclRole  */
            $aclRole = $this->myfavOrgAclRoleRepository->search($criteria, $context)->first();
            $extensions['myfavOrgCustomerExtension']['myfavOrgAclRole'] = $aclRole;

            // Load aclRole Attributes indexed.
            $indexedRoleAttributes = [];

            if(null !== $aclRole) {
                $indexedRoleAttributes = $aclRole->getAttributesIndexByTechnicalName();
            }

            $extensions['myfavOrgCustomerExtension']['indexedRoleAttributes'] = $indexedRoleAttributes;

        }
    }
}