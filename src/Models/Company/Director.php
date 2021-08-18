<?php

namespace SynergiTech\Creditsafe\Models\Company;

use SynergiTech\Creditsafe\Models\Company;

/**
 * This class contains all data relating to the Director and Company.
 */
class Director
{
    protected $company;
    protected $directorDetails;
    protected $current;

    /**
     * Function constructs the Director Class.
     *
     * @param Company $company         Used to store a company data in the Director Class
     * @param array   $directorDetails Directors Data that needs to be stored in the Director Class
     * @param bool    $current         Boolean used to indicate if the director is a current or previous director
     */
    public function __construct(Company $company, array $directorDetails, bool $current)
    {
        $this->company = $company;
        $this->directorDetails = $directorDetails;
        if (isset($this->directorDetails['dateOfBirth'])) {
            $this->directorDetails['dateOfBirth'] = new \DateTime($this->directorDetails['dateOfBirth']);
        }

        $this->directorDetails['positions'] = array_map(function ($position) {
            //Add if statement to check if position exists
            if (isset($position['dateAppointed'])) {
                //Can't assign datetime due to previousDirectors not having dateAppointed
                $position['dateAppointed'] = new \DateTime($position['dateAppointed']);
            }

            return $position;
        }, $this->directorDetails['positions']);
        $this->current = $current;
    }

    /**
     * Checks if the director is a current director.
     *
     * @return bool Returns if the current variable is true
     */
    public function isCurrent(): bool
    {
        return $this->current === true;
    }

    /**
     * Checks if the director is a previous director.
     *
     * @return bool Return if the previous variable is not true
     */
    public function isPrevious(): bool
    {
        return $this->current !== true;
    }

    /**
     * @return string Returns the Directors ID
     */
    public function getID(): string
    {
        return $this->directorDetails['id'] ?? '';
    }

    /**
     * @return string Returns the gender of the Director
     */
    public function getGender(): string
    {
        return $this->directorDetails['gender'] ?? '';
    }

    /**
     * @return DateTime | null Returns the Date Of Birth of  the Director
     */
    public function getDateOfBirth(): ?\DateTime
    {
        if (isset($this->directorDetails['dateOfBirth'])) {
            return $this->directorDetails['dateOfBirth'];
        }

        return null;
    }

    /**
     * @return array Returns an array  containing the position and the date appointed
     */
    public function getPositions(): array
    {
        return $this->directorDetails['positions'] ?? [];
    }

    /**
     * @return string Returns the directors name
     */
    public function getName(): string
    {
        return $this->directorDetails['name'] ?? '';
    }

    /**
     * @return array Returns the directors address in an array
     */
    public function getAddress(): array
    {
        return $this->directorDetails['address'] ?? [];
    }
}
