<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * [Shareholder description].
 */
class Shareholder
{
    protected $company;
    protected $shareholderDetails;

    /**
     * [__construct description].
     *
     * @param Company $company            Used to store the client in the Company Class
     * @param array   $shareholderDetails Shareholder Data that needs to be stored in the Shareholder Class
     */
    public function __construct(Company $company, array $shareholderDetails)
    {
        $this->company = $company;
        $this->shareholderDetails['name'] = $shareholderDetails['name'] ?? null;
        $this->shareholderDetails['shareType'] = $shareholderDetails['shareType'] ?? null;
        $this->shareholderDetails['numberOfSharesOwned'] = $shareholderDetails['numberOfSharesOwned'] ?? null;
        $this->shareholderDetails['percentSharesHeld'] = $shareholderDetails['percentSharesHeld'] ?? null;
        $this->shareholderDetails['shareholderType'] = $shareholderDetails['shareholderType'] ?? null;
    }

    /**
     * @return string Returns Shareholder Name
     */
    public function getShareHolderName(): string
    {
        return $this->shareholderDetails['name'] ?? '';
    }

    /**
     * @return string Returns the type of shareholder
     */
    public function getshareholderType(): string
    {
        return $this->shareholderDetails['shareholderType'] ?? '';
    }

    /**
     * @return string Returns the shareholder percetage
     */
    public function getShareHolderPercentage(): string
    {
        return $this->shareholderDetails['percentSharesHeld'] ?? '';
    }

    /**
     * @return string Returns the num of shares owned
     */
    public function getNumOfSharesOwned(): string
    {
        return $this->shareholderDetails['numberOfSharesOwned'] ?? '';
    }

    /**
     * @return string Returns the share type
     */
    public function getShareType(): string
    {
        return $this->shareholderDetails['shareType'] ?? '';
    }
}
