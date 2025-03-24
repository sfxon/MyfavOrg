<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\RouterInterface;

class AccessRightsService
{
    public function __construct(
        private readonly RouterInterface $router,)
    {
    }

    /**
     * hasRight
     *
     * @param  SalesChannelContext $salesChannelContext
     * @param  string $accessRightTechnicalName
     * @return ?EntitySearchResult
     */
    public function hasRight(SalesChannelContext $salesChannelContext, string $accessRightTechnicalName): bool
    {
        // If this is not an employee account, but a general user, access is always granted.
        if(!property_exists($salesChannelContext, 'myfavOrgEmployeeId') || $salesChannelContext->myfavOrgEmployeeId === null) {
            return true;
        }

        $extensions = $salesChannelContext->getExtensions();

        if(isset($extensions['myfavOrgEmployeeAcl'])) {
            $employeeAcl = $extensions['myfavOrgEmployeeAcl'];
            $result = $employeeAcl->get($accessRightTechnicalName);

            if($result !== null) {
                // If the right has been actively found, it is set for this account or role.
                return true;
            }
        }

        return false;
    }

    /**
     * validate
     *
     * @param  SalesChannelContext $salesChannelContext
     * @param  string $accessRightTechnicalName
     * @param  ?string redirectRoute
     * @return ?EntitySearchResult
     */
    public function validate(SalesChannelContext $salesChannelContext, string $accessRightTechnicalName, ?string $redirectRoute = null): bool
    {
        if($this->hasRight($salesChannelContext, $accessRightTechnicalName, $redirectRoute)) {
            return true;
        }

        // If a redirect is what is intended -> redirect.
        if($redirectRoute !== null) {
            $redirectResponse = new RedirectResponse($this->router->generate($redirectRoute));
            $redirectResponse->send();
            die();
        }

        return false;
    }
}