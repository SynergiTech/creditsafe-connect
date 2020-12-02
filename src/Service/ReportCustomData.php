<?php


namespace SynergiTech\Creditsafe\Service;

class ReportCustomData
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param $countryId
     * @return mixed
     * @see https://raw.githubusercontent.com/creditsafe/connect-docs/master/cs_connectv1-12.json
     */
    public function get($countryId)
    {
        return $this->client->get('reportcustomdata/' . $countryId);
    }
}