<?php declare(strict_types=1);

namespace Myfav\Org\Decorator\Core\Checkout\Customer\SalesChannel;

use Myfav\Org\Service\EmployeeLoginService;
use Shopware\Core\Checkout\Customer\SalesChannel\AbstractLoginRoute;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Checkout\Customer\Service\EmailIdnConverter;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\RateLimiter\RateLimiter;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['store-api']])]
#[Package('checkout')]
class LoginRoute extends AbstractLoginRoute
{
    /**
     * @internal
     */
    public function __construct(
        private readonly AbstractLoginRoute $decorated,
        private readonly AccountService $accountService,
        private readonly EmployeeLoginService $employeeLoginService,
        private readonly RequestStack $requestStack,
        private readonly RateLimiter $rateLimiter
    ) {
    }

    public function getDecorated(): AbstractLoginRoute
    {
        return $this->decorated;
    }

    #[Route(path: '/store-api/account/login', name: 'store-api.account.login', methods: ['POST'])]
    public function login(RequestDataBag $requestDataBag, SalesChannelContext $salesChannelContext): ContextTokenResponse
    {
        $employeeDataBag = clone $requestDataBag;
        EmailIdnConverter::encodeDataBag($employeeDataBag);
        $email = (string) $employeeDataBag->get('email', $employeeDataBag->get('username'));

        $contextTokenResponse = $this->employeeLoginService->loginByCredentials($email, $employeeDataBag->get('password'), $salesChannelContext);

        // Do original login, if the employeeLogin failed.
        if($contextTokenResponse === false) {
            $contextTokenResponse = $this->decorated->login($requestDataBag, $salesChannelContext);
            $this->employeeLoginService->removeEmployeeFromSession($contextTokenResponse->getToken(), $salesChannelContext);

            //$this->employeeLoginService->unsetMyfavOrgEmployeeIdInContext($salesChannelContext, $contextTokenResponse->getToken());
            return $contextTokenResponse;
        }

        return $contextTokenResponse;
    }
}