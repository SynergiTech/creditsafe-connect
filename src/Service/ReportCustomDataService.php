<?php

namespace SynergiTech\Creditsafe\Service;

class ReportCustomDataService
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $countryCode An ISO/Alpha-2 country code to display any special mandatory parameters
     * when ordering a Credit Report in that territory.
     * @return mixed
     */
    public function get(string $countryCode)
    {
        return $this->client->get('reportcustomdata/' . $countryCode);
    }
}
