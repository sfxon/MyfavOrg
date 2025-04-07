<?php declare(strict_types=1);

namespace Myfav\Org\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Country\CountryCollection;
use Shopware\Core\System\Country\SalesChannel\AbstractCountryRoute;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class CountryService
{
    public function __construct(
        private readonly AbstractCountryRoute $countryRoute,)
    {
    }

    /**
     * loadList
     *
     * @param  SalesChannelContext $salesChannelContext
     * @return EntitySearchResult
     */
    public function loadSalesChannelCountries(SalesChannelContext $salesChannelContext): CountryCollection
    {
        $countries = $this->countryRoute->load(new Request(), new Criteria(), $salesChannelContext)->getCountries();
        $countries->sortCountryAndStates();
        return $countries;
    }
}