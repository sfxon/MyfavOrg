<?php

declare(strict_types=1);

namespace Myfav\Org\Storefront\Subscriber;

use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OrderPlacedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityRepository $orderRepository,) 
    {
    }

    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CartConvertedEvent::class => 'onCartConvertedEvent'
        ];
    }

    /**
     * onCartConvertedEvent
     *
     * @param  CartConvertedEvent $event
     * @return void
     */
    public function onCartConvertedEvent(CartConvertedEvent $event): void
    {
        /*
        $salesChannelContext = $event->getSalesChannelContext();
        $extensions = $salesChannelContext->getExtensions();
        $myfavOrgEmployee = null;
        $convertedCart = $event->getConvertedCart();

        if(isset($extensions['myfavOrgEmployee'])) {
            $myfavOrgEmployee = $extensions['myfavOrgEmployee'];
        }

        // If employee is set in salesChannelContext - write employeeData into customFields before they get saved.
        if($myfavOrgEmployee !== null)
        {
            $convertedCart['customFields']['myfav_org_order_data_employee_id'] = $myfavOrgEmployee->getId();
            $convertedCart['customFields']['myfav_org_order_data_customer_id'] = $myfavOrgEmployee->getCustomerId();
            $convertedCart['customFields']['myfav_org_order_data_salutation_id'] = $myfavOrgEmployee->getSalutationId();
            $convertedCart['customFields']['myfav_org_order_data_salutation'] = $myfavOrgEmployee->getSalutation()->getDisplayName();
            $convertedCart['customFields']['myfav_org_order_data_firstname'] = $myfavOrgEmployee->getFirstName();
            $convertedCart['customFields']['myfav_org_order_data_lastname'] = $myfavOrgEmployee->getLastName();
            $convertedCart['customFields']['myfav_org_order_data_position'] = $myfavOrgEmployee->getPosition();
            $convertedCart['customFields']['myfav_org_order_data_email'] = $myfavOrgEmployee->getEmail();
            $convertedCart['customFields']['myfav_org_order_data_title'] = $myfavOrgEmployee->getTitle();

            // Modifizierte Daten zurücksetzen
            $event->setConvertedCart($convertedCart);
        }
        */
    }
}
