<?php

namespace SynergiTech\Creditsafe\Models;

/**
 * This class contains all data relating to the CompanySearchResult
 */
class CompanySearchResult
{
    protected $client;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var string|null
     */
    protected $regNo;

    /**
     * @var string|null
     */
    protected $safeNo;

    /**
     * @var array|null
     */
    protected $vatNo;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var array|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var mixed|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $officeType;

    /**
     * @var array|null
     */
    protected $tradingNames;

    /**
     * @var string|null
     */
    protected $dateOfLatestChange;

    /**
     * @var string|null
     */
    protected $activityCode;

    /**
     * Function constructs the CompanySearchResult Class
     * @param  $client     Used to store the client in the CompanySearchResult Class
     * @param array $companyDetails Company Data that needs to be stored in the CompanySearchResult Class
     *
     */
    public function __construct($client, array $companyDetails)
    {
        $this->client = $client;
        $this->id = $companyDetails['id'];
        $this->country = $companyDetails['country'] ?? null;
        $this->regNo = $companyDetails['regNo'] ?? null;
        $this->safeNo = $companyDetails['safeNo'] ?? null;
        $this->name = $companyDetails['name'] ?? null;
        $this->address = $companyDetails['address'] ?? null;
        $this->status = $companyDetails['status'] ?? null;
        $this->type = $companyDetails['type'] ?? null;
        $this->officeType = $companyDetails['officeType'] ?? null;
        $this->vatNo = $companyDetails['vatNo'] ?? [];
        $this->tradingNames = $companyDetails['tradingNames'] ?? [];
        $this->dateOfLatestChange = $companyDetails['dateOfLatestChange'] ?? null;
    }

    /**
     *
     * @return string Get ID
     */
    public function getID(): string
    {
        return $this->id;
    }

    /**
     * Get Country
     * @return string Get Country
     */
    public function getCountry(): string
    {
        return $this->country ?? '';
    }

    /**
     *
     * @return string Get Reference Number
     */
    public function getRefNo(): string
    {
        return $this->regNo ?? '';
    }

    /**
     *
     * @return string Get Safe Number
     */
    public function getSafeNo(): string
    {
        return $this->safeNo ?? '';
    }

    /**
     *
     * @return string Get Company  Name
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     *
     * @return array Get Address
     */
    public function getAddress(): array
    {
        return $this->address ?? [];
    }

    /**
     *
     * @return string Get Status
     */
    public function getStatus(): string
    {
        return $this->status ?? '';
    }

    /**
     *
     * @return string Get Type
     */
    public function getType(): string
    {
        return $this->type ?? '';
    }

    /**
     * @return string Get OfficeType
     */
    public function getOfficeType(): string
    {
        return $this->officeType ?? '';
    }

    /**
     * @return array
     */
    public function getTradingNames(): array
    {
        return $this->tradingNames ?? [];
    }

    /**
     * @return array
     */
    public function getVatNo(): array
    {
        return $this->vatNo ?? [];
    }

    /**
     *
     * @return DateTime Get Date Of Latest Change
     */
    public function getDateOfLatestChange(): \DateTime
    {
        return  new \DateTime($this->dateOfLatestChange);
    }
    /**
     *  Gets the company from a company searches result
     * @return Company Returns the Company Report
     */
    public function get(): Company
    {
        return $this->client->companies()->get($this->getID());
    }
}
