<?php

namespace SynergiTech\Creditsafe;

/*
    This class is used to help paginate the results of endpoints
*/

use SynergiTech\Creditsafe\Models\CompanyPortfolioSearchResult;

class ListResult implements \Iterator
{
    protected $client;
    protected $targetClass;
    protected $endpoint;
    protected $params;
    protected $pageSize = 10;
    protected $currentPagePos = null;
    protected $currentRecordPos = null;
    protected $currentRecordSet = [];
    protected $maxPageRecord;
    protected $minPageRecord;

    /**
     * This constructor is used to build the ListResult
     * @param Client $client Used to set the client in the ListResult Class
     * @param string $targetClass Used to set a dynamic class in the LisrResult Class
     *  allowing for ListResult to be used on different classes
     * @param string $endpoint Used to set the endpoint for which the ListResult Class is using
     * @param array $params Used tp set the params for which the ListResult Class is using
     */
    public function __construct(Client $client, string $targetClass, string $endpoint, array $params = [])
    {
        $this->client = $client;
        $this->targetClass = $targetClass;
        $this->endpoint = $endpoint;
        $this->params = $params;

        // Defaults
        $page = ($targetClass == CompanyPortfolioSearchResult::class) ? 0 : 1;
        $this->page($page);
    }

    /**
     * Set PageSize
     * @param int $size Stores the PageSize
     * @return self
     */
    public function setPageSize(int $size): self
    {
        $this->pageSize = $size;
        $this->rewind();
        return $this;
    }

    /**
     * Get  Params
     * @return array Return Params
     */
    private function getParams(): array
    {
        // Temp fix for Creditsafe error when paginating in portfolio search
        if ($this->targetClass == CompanyPortfolioSearchResult::class) {
            return $this->params;
        }
        $pageParams = ['page' => $this->currentPagePos];
        if ($this->pageSize !== null) {
            $pageParams['pageSize'] = $this->pageSize;
        }
        return array_merge($this->params, $pageParams);
    }

    /**
     * Get Page
     * @param int $num Passes the page number
     * @return array  Returns  the current Record Set
     */
    public function page(int $num): array
    {
        if ($num === $this->currentPagePos) {
            return $this->currentRecordSet;
        }

        $this->currentPagePos = $num;
        $this->currentRecordPos = $this->pageSize * ($this->currentPagePos - 1);
        $this->minPageRecord = $this->currentRecordPos;
        $this->currentRecordSet = $this->fetchPage();
        $this->maxPageRecord = $this->currentRecordPos + $this->pageSize - 1;

        return $this->currentRecordSet;
    }

    /**
     * fetch Page
     * @return array Returns the results for a page
     */
    private function fetchPage(): array
    {
        $results = [];
        $resultSet = $this->client->get($this->endpoint, $this->getParams());

        $fetchResults = ($resultSet['companies'] ?? $resultSet['data']);
        foreach ($fetchResults as $company) {
            $results[] = new $this->targetClass($this->client, $company);
        }

        return $results;
    }

    /**
     *  Rewind  to the first page
     * @return  void
     */
    public function rewind(): void
    {
        $page = ($this->targetClass == CompanyPortfolioSearchResult::class) ? 0 : 1;
        $this->page($page);
    }

    /**
     * Get the current page
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->currentRecordSet;
    }

    /**
     * Get the next page
     * @return void
     */
    public function next(): void
    {
        $this->currentRecordPos++;
        if ($this->currentRecordPos > $this->maxPageRecord) {
            $this->page($this->currentPagePos + 1);
        }
    }

    /**
     * Get the key of a position
     * @return int
     */
    public function key(): int
    {
        return $this->currentRecordPos - ($this->pageSize * ($this->currentPagePos - 1));
    }

    /**
     * Check if a position is valid
     * @return bool
     */
    public function valid(): bool
    {
        return !($this->key() >= count($this->currentRecordSet) && count($this->currentRecordSet) != $this->pageSize);
    }
}
