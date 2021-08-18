<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * This class contains all data relating to the FinancialStatement and Company.
 */
class FinancialStatement
{
    protected $company;
    protected $statementDetails;

    /**
     * Function constructs the FinancialStatement Class.
     *
     * @param Company $company          Used to store a company data in the Director Class
     * @param array   $statementDetails Financial Statement Data that needs to be stored in the FinancialStatement Class
     */
    public function __construct(Company $company, $statementDetails)
    {
        $this->company = $company;
        $this->statementDetails = $statementDetails;
        $this->statementDetails['yearEndDate'] = new \DateTime($this->statementDetails['yearEndDate']);
    }

    /**
     * @return array Returns an array of profile and loss variables
     */
    public function getProfitAndLoss(): array
    {
        return $this->statementDetails['profitAndLoss'] ?? [];
    }

    /**
     * @return array Returns an array of variables relating to a balanceSheet
     */
    public function getBalanceSheet(): array
    {
        return $this->statementDetails['balanceSheet'] ?? [];
    }

    /**
     * @return string Return contingentLiabilities
     */
    public function getContingentLiabilities(): string
    {
        return $this->statementDetails['otherFinancials']['contingentLiabilities'] ?? '';
    }

    /**
     * @return float | null Return workingCapital
     */
    public function getWorkingCapital(): ?float
    {
        return $this->statementDetails['otherFinancials']['workingCapital'];
    }

    /**
     * @return float | null Return net worth
     */
    public function getNetWorth(): ?float
    {
        return $this->statementDetails['otherFinancials']['netWorth'];
    }

    /**
     * @return array Get Ratio statistics like currentratio ,  currentdebtratio etc
     */
    public function getRatios(): array
    {
        return $this->statementDetails['ratios'] ?? [];
    }

    /**
     * @return \DateTime | null Get the Financial Year End Date
     */
    public function getYearEndDate(): ?\DateTime
    {
        return $this->statementDetails['yearEndDate'];
    }

    /**
     * @return int | null Gets the number of accountable weeks
     */
    public function getNumberOfWeeks(): ?int
    {
        return $this->statementDetails['numberOfWeeks'];
    }

    /**
     * @return string Returns the Currency as a string
     */
    public function getCurrency(): string
    {
        return $this->statementDetails['currency'] ?? [];
    }

    /**
     * @return bool Returns true or false if consolidated Accounts
     */
    public function getConsolidatedAccounts(): bool
    {
        return $this->statementDetails['consolidatedAccounts'];
    }

    /**
     * @return string Return Type
     */
    public function getType(): string
    {
        return $this->statementDetails['type'] ?? [];
    }
}
