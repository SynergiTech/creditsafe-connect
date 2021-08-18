<?php

namespace SynergiTech\Creditsafe\Tests;

use SynergiTech\Creditsafe\Client;

class ListResult extends \SynergiTech\Creditsafe\Tests\Base
{
    /**
     * @dataProvider providerMaxPageLength
     */
    public function testIteratorDoesNotExceedMaxPage($guzzle)
    {
        $client = new Client([
            'http_client' => $guzzle,
        ]);

        $iterator = $client->companies()->search([
            'countries' => 'GB',
            'name' => 'Test',
        ]);

        $this->assertInstanceOf(\Iterator::class, $iterator);

        $array = iterator_to_array($iterator);
    }

    public function providerMaxPageLength()
    {
        return $this->dataToGuzzleMock(require 'data/iterator/max_page_length.php');
    }
}
