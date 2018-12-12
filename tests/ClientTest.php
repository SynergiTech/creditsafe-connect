<?php

namespace SynergiTech\Creditsafe\Tests;

use SynergiTech\Creditsafe\Client;

class ClientTest extends \SynergiTech\Creditsafe\Tests\Base
{
    /**
     * @dataProvider providerAuthorizationToken
     */
    public function testAuthorizationSuccess($guzzle)
    {
        $client = new Client([
            'http_client' => $guzzle,
            'username' => '',
            'password' => '',
        ]);
        $client->authenticate();

        $this->assertNotNull($client->getToken());
        $this->assertSame('example@example.org', $client->getToken()->getClaim('email'));
    }

    public function providerAuthorizationToken()
    {
        return $this->dataToGuzzleMock(require 'data/authorization/valid_token.php');
    }

    /**
    * @dataProvider providerInvalidAuthorizationToken
    */
    public function testAuthorizationError($guzzle)
    {
        $client = new Client([
            'http_client' => $guzzle,
            'username' => '',
            'password' => '',
        ]);

        $this->expectException(\SynergiTech\Creditsafe\Exception\Unauthorized::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('authentication failed');

        $client->authenticate();
    }

    public function providerInvalidAuthorizationToken()
    {
        return $this->dataToGuzzleMock(require 'data/authorization/invalid_token.php');
    }
}
