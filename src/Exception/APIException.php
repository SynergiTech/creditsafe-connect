<?php

namespace SynergiTech\Creditsafe\Exception;

/**
 * This class is used to handle API Expections.
 */
class APIException extends \RuntimeException
{
    protected $correlationID = null;

    /**
     *  Sets the CorrelationID.
     *
     * @param string $correlationID Contains the CorrelationID
     */
    public function setCorrelationID(string $correlationID): void
    {
        $this->correlationID = $correlationID;
    }

    /**
     * Get CorrelationID.
     *
     * @return ?string Contains the CorrelationID
     */
    public function getCorrelationID(): ?string
    {
        return $this->correlationID;
    }
}
