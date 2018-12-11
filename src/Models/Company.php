<?php

namespace SynergiTech\Creditsafe\Models;

use SynergiTech\Creditsafe\Models\Company\CreditScore;

/**
 *  This class contains all data relating to the company
 */
class Company
{

    private $client;
    private $companyID;
    private $businessName;
    private $registeredCompanyName;
    private $companyRegistrationNumber;
    private $country;
    private $companyRegistrationDate;
    private $mainAddress;
    private $otherAddresses;
    private $commentaries;
    private $history;
    private $currentDirectors;
    private $previousDirectors;
    private $creditScore;
    private $mortgageSummary;
    private $mortgages;
    private $negativeInfo;
    private $rawDetails;
    private $financialStatements;

    /**
     * Function constructs the Company Class
     * @param array $client         Used to store the client in the Company Class
     * @param array $companyDetails  Company Data that needs to be stored in the Company Class
     */
    public function __construct($client, array $companyDetails)
    {
        $this->client = $client;
        $this->companyID = $companyDetails['report']['companyId'];
        $this->businessName = $companyDetails['report']['companyIdentification']['basicInformation']['businessName'];
        $this->registeredCompanyName = $companyDetails['report']['companyIdentification']['basicInformation']['registeredCompanyName'];
        $this->companyRegistrationNumber = $companyDetails['report']['companyIdentification']['basicInformation']['companyRegistrationNumber'];
        $this->country = $companyDetails['report']['companyIdentification']['basicInformation']['country'];
        $this->companyRegistrationDate = $companyDetails['report']['companyIdentification']['basicInformation']['companyRegistrationDate'];
        $this->mainAddress = $companyDetails['report']['contactInformation']['mainAddress'];
        $this->otherAddresses = $companyDetails['report']['contactInformation']['otherAddresses'];

        $this->currentDirectors = array_map(function ($director) {
            return new Company\Director($this, $director, true);
        }, $companyDetails['report']['directors']['currentDirectors']);

        $this->previousDirectors = array_map(function ($director) {
            return new Company\Director($this, $director, false);
        }, $companyDetails['report']['directors']['previousDirectors'] ?? []);

        $this->financialStatements = array_map(function ($statement) {
            return new Company\FinancialStatement($this, $statement);
        }, $companyDetails['report']['financialStatements'] ?? []);

        $this->history = array_map(function ($history) {
            $history['date'] = new \DateTime($history['date']);
            return $history;
        }, $companyDetails['report']['additionalInformation']['companyHistory'] ?? []);

        $this->commentaries = $companyDetails['report']['additionalInformation']['commentaries'];
        $this->creditScore = new Company\CreditScore($this, $companyDetails['report']['creditScore']);
        $this->mortgageSummary = $companyDetails['report']['additionalInformation']['mortgageSummary'];
        $this->mortgages = $companyDetails['report']['additionalInformation']['mortgageDetails'] ?? null;
        $this->negativeInfo = $companyDetails['report']['negativeInformation'];
        $this->rawDetails = $companyDetails;
    }

    /**
     *
     * @return string Return Company ID
     */
    public function getCompanyID() : string
    {
        return $this->companyID;
    }

    /**
     *
     * @return string Return Business Name
     */
    public function getBusinessName() : string
    {
        return $this->businessName;
    }

    /**
     *
     * @return string Return Registered Company Name
     */
    public function getRegisteredCompanyName() : string
    {
        return $this->registeredCompanyName;
    }

    /**
     *
     * @return string  Return Company Registeration Number
     */
    public function getCompanyRegistrationNumber() : string
    {
        return $this->companyRegistrationNumber;
    }

    /**
     *
     * @return string  Return Country
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     *
     * @return DateTime Return Company Registration Date
     */
    public function getCompanyRegistrationDate() : \DateTime
    {
        return new \DateTime($this->companyRegistrationDate);
    }

    /**
     *
     * @return array Return Main Address
     */
    public function getMainAddress() : array
    {
        return $this->mainAddress;
    }

    /**
     *
     * @return array Return otherAddresses
     */
    public function getOtherAddresses() : array
    {
        return $this->otherAddresses;
    }

    /**
     *
     * @return array Return an array of current directors
     */
    public function getCurrentDirectors() : ?array
    {
        return $this->currentDirectors;
    }

    /**
     *
     * @return array Return an array of previous directors
     */
    public function getPreviousDirectors() : array
    {
        return $this->previousDirectors;
    }

    /**
     *
     * @return array Return an array of the whole company request
     */
    public function getRawDetails() : array
    {
        return $this->rawDetails;
    }

    /**
     *
     * @return CreditScore Returns the CreditScore of the company
     */
    public function getCreditScore() : CreditScore
    {
        return $this->creditScore;
    }

    /**
     *
     * @return array Returns the commentaries of the company
     */
    public function getCommentaries() : array
    {
        return $this->commentaries;
    }

    /**
     *
     * @return array Return the history of the company
     */
    public function getHistory() : array
    {
        return $this->history;
    }

    /**
     *
     * @return array Return the mortgages summary
     */
    public function getMortgageSummary() : array
    {
        return $this->mortgageSummary;
    }

    /**
     *
     * @return array Return all company mortgages
     */
    public function getMortgages() : ?array
    {
        return $this->mortgages;
    }

    /**
     *
     * @return array Get Negative Information
     */
    public function getNegativeInformation() : array
    {
        return $this->negativeInfo;
    }

    /**
     *  Gets financialStatements
     * @return array Returns an array of financial statements
     */
    public function getFinancialStatements() : array
    {
        return $this->financialStatements;
    }
}
