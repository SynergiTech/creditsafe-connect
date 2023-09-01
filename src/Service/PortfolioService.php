<?php

namespace SynergiTech\Creditsafe\Service;

use \SynergiTech\Creditsafe\ListResult;
use \SynergiTech\Creditsafe\Models\CompanySearchResult;
use \SynergiTech\Creditsafe\Models\CompanyPortfolioSearchResult;
use \SynergiTech\Creditsafe\Models\Company;

/**
 * This class is used by the client to call endpoints relating to a portfolio
 */
class PortfolioService
{
    protected $client;
    protected $id;

    /**
     * This constructor builds the CompanyServices Class
     * @param array $client This variable stores the client in the CompanyServices Class
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * This function is used to call the endpoint that adds a company to a portfolio
     * @param string $portfolioID is the id of the portfolio the company needs to be
     * added to, this is used in the endpoint
     * @param array $params Contains params that can be passed to the endpoint
     * @return array    Returns the results of the addCompany endpoint
     */
    public function addCompany(array $params): array
    {
        return $this->client->post('monitoring/portfolios/' . (\config('creditsafe.portfolio_id')) . '/companies', $params);
    }
    /**
     * This function is used to call the endpoint that removes a company from a portfolio
     * @param string $creditsafeConnectID is the connectID of the company (from Creditsafe),
     * this is used in the endpoint
     * @return array    Returns the results of the removeCompany endpoint
     */
    public function removeCompany(string $creditsafeConnectID): array
    {
        return $this->client->delete('monitoring/portfolios/' . (\config('creditsafe.portfolio_id')) . '/companies/' . $creditsafeConnectID);
    }
    /**
     * This function is used to call the endpoint that retrieves all company in a portfolio
     * @return ListResult  Returns the results of the getCompanies endpoint
     */
    public function getCompanies(string $portfolioID = null) : ListResult
    {
        $list = new ListResult($this->client, CompanyPortfolioSearchResult::class, 'monitoring/portfolios/' . ($portfolioID ?? (\config('creditsafe.portfolio_id')) ). '/companies');
        return $list;
    }
}
