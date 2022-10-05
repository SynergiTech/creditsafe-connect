<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * This class contains all data relating to a Previous Director of a Company
 */
class PreviousDirector
{
    protected $company;
    protected $directorDetails;

    /**
     * @param Company $company         Used to store a company data in the Director Class
     * @param array $directorDetails   Directors Data that needs to be stored in the Director Class
     */
    public function __construct(Company $company, array $directorDetails)
    {
        $this->company = $company;
        $this->directorDetails = $directorDetails;
    }

    /**
     * @deprecated in favour of checking class
     */
    public function isCurrent(): bool
    {
        return false;
    }

    /**
     * @deprecated in favour of checking class
     */
    public function isPrevious(): bool
    {
        return true;
    }

    /**
     * @return string the ID of the Previous Director
     */
    public function getID(): string
    {
        return $this->directorDetails['id'] ?? '';
    }

    /**
     * @return string the gender of the Previous Director
     */
    public function getGender(): string
    {
        return $this->directorDetails['gender'] ?? '';
    }

    /**
     * @return string the name of the Previous Director
     */
    public function getName(): string
    {
        return $this->directorDetails['name'] ?? '';
    }
}
