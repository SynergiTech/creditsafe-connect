<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * This class contains all data relating to the CreditScore and Company.
 */
class CreditScore
{
    protected $company;
    protected $currentCreditRating;
    protected $currentContractLimit;
    protected $previousCreditRating;
    protected $latestRatingChangeDate;

    /**
     * Function constructs the CreditScore Class.
     *
     * @param Company $company     Used to store a company data in the creditScore Class
     * @param array   $creditScore CreditScore Data that needs to be stored in the creditScore Class
     */
    public function __construct(Company $company, array $creditScore)
    {
        $this->company = $company;
        $this->currentCreditRating = $creditScore['currentCreditRating'] ?? null;
        $this->currentContractLimit = $creditScore['currentContractLimit'] ?? null;
        $this->previousCreditRating = $creditScore['previousCreditRating'] ?? null;
        $this->latestRatingChangeDate = null;
        if (isset($creditScore['latestRatingChangeDate'])) {
            $this->latestRatingChangeDate = new \DateTime($creditScore['latestRatingChangeDate']);
        }
    }

    /**
     * @return array | null Returns the currentCreditRating data  as an array
     */
    public function getCurrentCreditRating(): ?array
    {
        return $this->currentCreditRating;
    }

    /**
     * @return array | null Returns the currentContractLimit data  as an array
     */
    public function getCurrentContractLimit(): ?array
    {
        return $this->currentContractLimit;
    }

    /**
     * @return array | null Returns the previousCreditRating data  as an array
     */
    public function getPreviousCreditRating(): ?array
    {
        return $this->previousCreditRating;
    }

    /**
     * @return \DateTime | null Returns the LastestRatingChangeDate in DateTime
     */
    public function getLatestRatingChangeDate(): ?\DateTime
    {
        return $this->latestRatingChangeDate;
    }
}
