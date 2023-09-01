<?php

namespace SynergiTech\Creditsafe\Models;

/**
 * This class contains all data relating to the CompanySearchResult
 */
class CompanyPortfolioSearchResult
{
    protected $client;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $safeNumber;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $countryCode;

    /**
     * @var string|null
     */
    protected $portfolioID;

    /**
     * @var string|null
     */
    protected $creditLimit;

    /**
     * @var datetime|null
     */
    protected $dateLastEvent;

    /**
     * @var mixed|null
     */
    protected $freeText;

    /**
     * @var string|null
     */
    protected $personalLimit;

    /**
     * @var array|null
     */
    protected $personalReference;

    /**
     * @var string|null
     */
    protected $ratingCommon;

    /**
     * @var string|null
     */
    protected $ratingLocal;

    /**
     * @var string|null
     */
    protected $companyStatus;

    /**
     * @var datetime|null
     */
    protected $dateAdded;

    /**
     * @var datetime|null
     */
    protected $dateModified;
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
        $this->safeNumber = $companyDetails['safeNumber'] ?? null;
        $this->name = $companyDetails['name'] ?? null;
        $this->address = $companyDetails['address'] ?? null;
        $this->countryCode = $companyDetails['countryCode'] ?? null;
        $this->portfolioID = $companyDetails['portfolioId'] ?? null;
        $this->creditLimit = $companyDetails['creditLimit'] ?? null;
        $this->dateLastEvent = $companyDetails['dateLastEvent'] ?? null;
        $this->freeText = $companyDetails['freeText'] ?? null;
        $this->personalLimit = $companyDetails['personalLimit'] ?? null;
        $this->personalReference = $companyDetails['personalReference'] ?? null;
        $this->ratingCommon = $companyDetails['ratingCommon'] ?? null;
        $this->ratingLocal = $companyDetails['ratingLocal'] ?? null;
        $this->companyStatus = $companyDetails['companyStatus'] ?? null;
        $this->dateAdded = $companyDetails['dateAdded'] ?? null;
        $this->dateModified = $companyDetails['dateModified'] ?? null;
    }

    /**
     *
     * @return string Get ID
     */
    public function getID() : string
    {
        return $this->id;
    }

    /**
     *
     * @return string Get Safe Number
     */
    public function getSafeNumber() : string
    {
        return $this->safeNumber ?? '';
    }

    /**
     *
     * @return string Get Company Name
     */
    public function getName() : string
    {
        return $this->name ?? '';
    }

    /**
     *
     * @return array Get Address
     */
    public function getAddress() : string
    {
        return $this->address ?? '';
    }

    /**
     * Get Country code
     * @return string Get Country code
     */
    public function getCountryCode() : string
    {
        return $this->countryCode ?? '';
    }
    /**
     * Get Portfolio ID
     * @return string Get Portfolio ID
     */
    public function getPortfolioID() : string
    {
        return $this->portfolioID ?? '';
    }
    /**
     *
     * @return string Get Reference Number
     */
    public function getRefNo() : string
    {
        return $this->regNo ?? '';
    }
    /**
     *
     * @return string Get Credit limit
     */
    public function getCreditLimit() : string
    {
        return $this->creditLimit ?? '';
    }

    /**
     *
     * @return string Get Date Last Event
     */
    public function getDateLastEvent() : \DateTime
    {
        return $this->dateLastEvent ?? '';
    }

    /**
     *
     * @return string Get Free Text
     */
    public function getFreeText() : string
    {
        return $this->freeText ?? '';
    }

    /**
     * @return string Get Personal Limit
     */
    public function getPersonalLimit() : string
    {
        return $this->personalLimit ?? '';
    }

    /**
     * @return string Get Personal Reference
     */
    public function getPersonalReference() : string
    {
        return $this->personalReference ?? '';
    }

    /**
     * @return string Get Rating Common
     */
    public function getRatingCommon() : string
    {
        return $this->ratingCommon ?? '';
    }
    /**
     * @return string Get Rating Local
     */
    public function getRatingLocal() : string
    {
        return $this->ratingLocal ?? '';
    }
    /**
     * @return string Get Company Status
     */
    public function getCompanyStatus() : string
    {
        return $this->companyStatus ?? '';
    }
    /**
     *
     * @return DateTime Get Date The Company Was Added To Portfolio
     */
    public function getDateAdded() : \DateTime
    {
        return  new \DateTime($this->dateAdded);
    }
    /**
     *
     * @return DateTime Get Date Modified
     */
    public function getDateModified() : \DateTime
    {
        return  new \DateTime($this->dateModified);
    }
    /**
     *  Gets the company from a company portfolio searchs result
     * @return Company Returns the Company Report
     */
    public function get() : Company
    {
        return $this->client->companies()->get($this->getID());
    }
}
