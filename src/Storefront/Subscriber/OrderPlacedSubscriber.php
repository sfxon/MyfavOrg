<?php

declare(strict_types=1);

namespace Myfav\Org\Storefront\Subscriber;

use Myfav\Org\Service\MyfavSalesChannelContextService;
use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OrderPlacedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MyfavSalesChannelContextService $myfavSalesChannelContextService,)
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
        $myfavOrgOrderExtension = [];
        $salesChannelContext = $event->getSalesChannelContext();

        // Load company.
        $company = $this->myfavSalesChannelContextService->getCompany($salesChannelContext);

        if($company !== null) {
            $companyId = $company->getId();
            $myfavOrgOrderExtension['myfavOrgCompanyId'] = $companyId;
        }

        // Get orderClearanceGroupId from customer extension.
        $customer = $salesChannelContext->getCustomer();

        if($customer !== null) {
            $customerExtensions = $customer->getExtensions();

            if(isset($customerExtensions['myfavOrgCustomerExtension'])) {
                $customerExtension = $customerExtensions['myfavOrgCustomerExtension'];

                // Only save orderClearanceGroup, if this order is to be cleared/approved.
                // Id 0196192887a871f38d43089598db212b is management. A manager should not have
                // to clear his own order.
                if(
                    isset($customerExtension['orderClearanceGroupId']) &&
                    $customerExtension['orderClearanceGroupId'] !== null &&
                    isset($customerExtension['orderClearanceRoleId']) &&
                    $customerExtension['orderClearanceRoleId'] != '0196192887a871f38d43089598db212b' &&
                    $customerExtension['orderClearanceRoleId'] != null)
                {
                    $orderClearanceGroupId = $customerExtension['orderClearanceGroupId'];

                    if($orderClearanceGroupId !== null) {
                        $myfavOrgOrderExtension['orderClearanceGroupId'] = $orderClearanceGroupId;
                    }
                }
            }
        }

        // Add additional data to order.
        if(count($myfavOrgOrderExtension) > 0) {
            $convertedCart = $event->getConvertedCart();
            $convertedCart['extensions']['myfavOrgOrderExtension'] = $myfavOrgOrderExtension;
            $event->setConvertedCart($convertedCart);
        }
    }
}