<?php

namespace SynergiTech\Creditsafe\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
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
        $this->assertSame('example@example.org', $client->getToken()->claims()->get('email'));
    }

    public static function providerAuthorizationToken()
    {
        return self::dataToGuzzleMock(require 'data/authorization/valid_token.php');
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

    public static function providerInvalidAuthorizationToken()
    {
        return self::dataToGuzzleMock(require 'data/authorization/invalid_token.php');
    }

    /**
     * A parsed token is re-serialised via Token::toString() and sent as the
     * Authorization header on subsequent API requests. This guards the
     * lcobucci/jwt v3 -> v4/v5 API change (no more (string) cast on Token).
     */
    public function testRequestSendsParsedTokenAsAuthorizationHeader()
    {
        $token = self::makeJwt(['email' => 'example@example.org', 'exp' => 2000000000]);

        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['token' => $token])),
            new Response(200, [], json_encode(['ok' => true])),
        ]));
        $stack->push(Middleware::history($history));

        $client = new Client([
            'http_client' => new GuzzleClient(['handler' => $stack]),
            'username' => '',
            'password' => '',
        ]);

        $result = $client->get('companies');

        $this->assertSame(['ok' => true], $result);

        $sentHeader = $history[1]['request']->getHeaderLine('Authorization');
        $this->assertNotSame('', $sentHeader);

        $reparsed = (new Parser(new JoseEncoder()))->parse($sentHeader);
        $this->assertSame('example@example.org', $reparsed->claims()->get('email'));
    }

    /**
     * A non-expired token is reused across requests: checkToken() calls
     * Token::isExpired() and must not re-authenticate. Guards the third
     * lcobucci/jwt API touchpoint (isExpired on a parsed token) under v4/v5.
     */
    public function testValidTokenIsReusedWithoutReauthenticating()
    {
        $token = self::makeJwt(['email' => 'example@example.org', 'exp' => 2000000000]);

        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['token' => $token])),
            new Response(200, [], json_encode(['first' => true])),
            new Response(200, [], json_encode(['second' => true])),
        ]));
        $stack->push(Middleware::history($history));

        $client = new Client([
            'http_client' => new GuzzleClient(['handler' => $stack]),
            'username' => '',
            'password' => '',
        ]);

        $client->get('companies');
        $client->get('companies');

        // authenticate (POST) happened exactly once; both reads reused the token.
        $this->assertCount(3, $history);
        $this->assertSame('authenticate', $history[0]['request']->getUri()->getPath());
        $this->assertSame('companies', $history[1]['request']->getUri()->getPath());
        $this->assertSame('companies', $history[2]['request']->getUri()->getPath());
    }

    private static function makeJwt(array $claims): string
    {
        $encode = fn (array $segment): string => rtrim(
            strtr(base64_encode(json_encode($segment)), '+/', '-_'),
            '='
        );

        return $encode(['typ' => 'JWT', 'alg' => 'HS256'])
            . '.' . $encode($claims)
            . '.' . $encode(['signature']);
    }
}
