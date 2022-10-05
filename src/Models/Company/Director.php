<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * This class contains all data relating to the Director and Company
 */
class Director
{
    protected $company;
    protected $directorDetails;
    protected $current;

    /**
     * @param Company $company         Used to store a company data in the Director Class
     * @param array $directorDetails   Directors Data that needs to be stored in the Director Class
     */
    public function __construct(Company $company, array $directorDetails)
    {
        $this->company = $company;
        $this->directorDetails = $directorDetails;
        if (isset($this->directorDetails['dateOfBirth'])) {
            $this->directorDetails['dateOfBirth'] =  new \DateTime($this->directorDetails['dateOfBirth']);
        }

        $this->directorDetails['positions'] = array_map(function ($position) {
            //Add if statement to check if position exists
            if (isset($position['dateAppointed'])) {
            //Can't assign datetime due to previousDirectors not having dateAppointed
                $position['dateAppointed'] = new \DateTime($position['dateAppointed']);
            }
            return $position;
        }, $this->directorDetails['positions']);
    }

    /**
     * @deprecated in favour of checking class
     */
    public function isCurrent(): bool
    {
        return true;
    }

    /**
     * @deprecated in favour of checking class
     */
    public function isPrevious(): bool
    {
        return false;
    }

    /**
     *
     * @return string the ID of the Director
     */
    public function getID(): string
    {
        return $this->directorDetails['id'] ?? '';
    }

    /**
     *
     * @return string the gender of the Director
     */
    public function getGender(): string
    {
        return $this->directorDetails['gender'] ?? '';
    }

    /**
     *
     * @return DateTime|null the Date Of Birth of the Director
     */
    public function getDateOfBirth(): ?\DateTime
    {
        if (isset($this->directorDetails['dateOfBirth'])) {
            return $this->directorDetails['dateOfBirth'];
        }

        return null;
    }

    /**
     *
     * @return array an array containing the position and the date appointed
     */
    public function getPositions(): array
    {
        return $this->directorDetails['positions'] ?? [];
    }

    /**
     *
     * @return string the name of the Director
     */
    public function getName(): string
    {
        return $this->directorDetails['name'] ?? '';
    }

    /**
     *
     * @return array the directors address in an array
     */
    public function getAddress(): array
    {
        return $this->directorDetails['address'] ?? [];
    }
}
