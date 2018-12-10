<?php

namespace SynergiTech\Creditsafe\Access;

use SynergiTech\Creditsafe\Client;

/**
 * This class is used to get access to the countries endpoint
 */

class Countries
{
    private $client;

    /**
     * This Constructor builds the Countries Class
     * @param Client $client This variable stores the client in the Countries Class
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * This access function is called by the client to get the access countries endpoint
     * @param  array
     * @return array  Returns the results of the endpoint in an array
     */
    public function access(array $params) : array
    {
        return $this->client->get('access/countries', $params);
    }
}
