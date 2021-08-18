<?php

namespace SynergiTech\Creditsafe\Service;

use SynergiTech\Creditsafe\Client;

/**
 * This class is used to call the Company Events Endpoint.
 */
class CompanyEventService
{
    protected $client;
    protected $id;

    /**
     * This Constructor builds the CompaniesEvents Class.
     *
     * @param array $client This variable stores the client in the CompaniesEvents Class
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * This function gets the companies Events endpoint.
     *
     * @param string $id     The ID of the company events we are searching for
     * @param array  $params Params for the endpoint
     *
     * @return array Returns the results of the endpoint
     */
    public function companyEvents(string $id, array $params): array
    {
        return $this->client->get('monitoring/companies/' . $id . '/events', $params);
    }
}
