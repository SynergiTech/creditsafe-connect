<?php

namespace SynergiTech\Creditsafe\Models;

use SynergiTech\Creditsafe\Models\Company\CreditScore;

/**
 *  This class contains all data relating to the company.
 */
class Company
{
    protected $client;
    protected $companyID;
    protected $businessName;
    protected $registeredCompanyName;
    protected $companyRegistrationNumber;
    protected $country;
    protected $companyRegistrationDate;
    protected $mainAddress;
    protected $otherAddresses;
    protected $commentaries;
    protected $history;
    protected $currentDirectors;
    protected $previousDirectors;
    protected $creditScore;
    protected $shareholders;
    protected $issuedShareCapital;
    protected $numberOfSharesIssued;
    protected $mortgageSummary;
    protected $mortgages;
    protected $negativeInfo;
    protected $rawDetails;
    protected $financialStatements;
    protected $personsWithSignificantControl;

    /**
     * Function constructs the Company Class.
     *
     * @param array $client         Used to store the client in the Company Class
     * @param array $companyDetails Company Data that needs to be stored in the Company Class
     */
    public function __construct($client, array $companyDetails)
    {
        $this->client = $client;
        $this->companyID = $companyDetails['report']['companyId'];
        $this->businessName =
            $companyDetails['report']['companyIdentification']['basicInformation']['businessName'] ?? null;
        $this->registeredCompanyName =
            $companyDetails['report']['companyIdentification']['basicInformation']['registeredCompanyName'] ?? null;
        $this->companyRegistrationNumber =
            $companyDetails['report']['companyIdentification']['basicInformation']['companyRegistrationNumber'] ?? null;
        $this->country = $companyDetails['report']['companyIdentification']['basicInformation']['country'] ?? null;

        if (isset($companyDetails['report']['companyIdentification']['basicInformation']['companyRegistrationDate'])) {
            $this->companyRegistrationDate =
                $companyDetails['report']['companyIdentification']['basicInformation']['companyRegistrationDate'];
        }

        $this->mainAddress = $companyDetails['report']['contactInformation']['mainAddress'] ?? [];
        $this->otherAddresses = $companyDetails['report']['contactInformation']['otherAddresses'] ?? [];

        $this->currentDirectors = array_map(function ($director) {
            return new Company\Director($this, $director, true);
        }, $companyDetails['report']['directors']['currentDirectors'] ?? []);

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

        $this->commentaries = $companyDetails['report']['additionalInformation']['commentaries'] ?? [];
        $this->creditScore = new Company\CreditScore($this, $companyDetails['report']['creditScore'] ?? []);
        $this->shareholders = array_map(function ($shareholder) {
            return new Company\Shareholder($this, $shareholder);
        }, $companyDetails['report']['shareCapitalStructure']['shareHolders'] ?? []);

        $this->numberOfSharesIssued =
            $companyDetails['report']['shareCapitalStructure']['numberOfSharesIssued'] ?? null;
        $this->issuedShareCapital = $companyDetails['report']['shareCapitalStructure']['issuedShareCapital'] ?? [];
        $this->mortgageSummary = $companyDetails['report']['additionalInformation']['mortgageSummary'] ?? [];
        $this->mortgages = $companyDetails['report']['additionalInformation']['mortgageDetails'] ?? null;
        $this->negativeInfo = $companyDetails['report']['negativeInformation'] ?? [];
        $this->rawDetails = $companyDetails;
        $this->personsWithSignificantControl = $companyDetails['report']['additionalInformation']['personsWithSignificantControl']['activePSC'] ?? [];
    }

    /**
     * @return string Return Company ID
     */
    public function getCompanyID(): string
    {
        return $this->companyID;
    }

    /**
     * @return string Return Business Name
     */
    public function getBusinessName(): string
    {
        return $this->businessName ?? '';
    }

    /**
     * @return string Return Registered Company Name
     */
    public function getRegisteredCompanyName(): string
    {
        return $this->registeredCompanyName ?? '';
    }

    /**
     * @return string Return Company Registeration Number
     */
    public function getCompanyRegistrationNumber(): string
    {
        return $this->companyRegistrationNumber ?? '';
    }

    /**
     * @return string Return Country
     */
    public function getCountry(): string
    {
        return $this->country ?? '';
    }

    /**
     * @return \DateTime Return Company Registration Date
     */
    public function getCompanyRegistrationDate(): ?\DateTime
    {
        return $this->companyRegistrationDate ? new \DateTime($this->companyRegistrationDate) : null;
    }

    /**
     * @return array Return Main Address
     */
    public function getMainAddress(): array
    {
        return $this->mainAddress ?? [];
    }

    /**
     * @return array Return otherAddresses
     */
    public function getOtherAddresses(): array
    {
        return $this->otherAddresses ?? [];
    }

    /**
     * @return array | null Return an array of current directors
     */
    public function getCurrentDirectors(): ?array
    {
        return $this->currentDirectors;
    }

    /**
     * @return array Return an array of previous directors
     */
    public function getPreviousDirectors(): array
    {
        return $this->previousDirectors ?? [];
    }

    /**
     * @return array Return an array of the whole company request
     */
    public function getRawDetails(): array
    {
        return $this->rawDetails ?? [];
    }

    /**
     * @return CreditScore Returns the CreditScore of the company
     */
    public function getCreditScore(): CreditScore
    {
        return $this->creditScore;
    }

    /**
     * @return array Returns a array of Shareholders for the company
     */
    public function getShareHolders(): array
    {
        return $this->shareholders ?? [];
    }

    /**
     * @return array Returns the commentaries of the company
     */
    public function getCommentaries(): array
    {
        return $this->commentaries ?? [];
    }

    /**
     * @return array Return the history of the company
     */
    public function getHistory(): array
    {
        return $this->history ?? [];
    }

    /**
     * @return array Return the mortgages summary
     */
    public function getMortgageSummary(): array
    {
        return $this->mortgageSummary ?? [];
    }

    /**
     * @return array Return all company mortgages
     */
    public function getMortgages(): ?array
    {
        return $this->mortgages;
    }

    /**
     * @return array | null Get Negative Information
     */
    public function getNegativeInformation(): array
    {
        return $this->negativeInfo ?? [];
    }

    /**
     *  Gets financialStatements.
     *
     * @return array Returns an array of financial statements
     */
    public function getFinancialStatements(): array
    {
        return $this->financialStatements ?? [];
    }

    /**
     * @return array Returns an array which ocontains the currency and value of shares
     */
    public function getIssuedShareCapital(): array
    {
        return $this->issuedShareCapital ?? [];
    }

    /**
     * @return string Returns the number of shares issued
     */
    public function getNumberOfSharesIssued(): ?int
    {
        return $this->numberOfSharesIssued;
    }
    
    /**
     *
     * @return array  Return personsWithSignificantControl
     */
    public function getPersonsWithSignificantControl() : array
    {
        return $this->personsWithSignificantControl ?? [];
    }
}
