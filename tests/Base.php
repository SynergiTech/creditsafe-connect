<?php

namespace SynergiTech\Creditsafe\Tests;

use PHPUnit\Framework\TestCase;
use SynergiTech\Creditsafe\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class Base extends TestCase
{
    public function dataToGuzzleMock($data)
    {
        return array_map(function ($set) {
            $responses = [];
            foreach ($set as $res) {
                $code = $res['code'] ?? 200;
                $headers = $res['headers'] ?? [];
                $body = $res['body'] ?? '';

                $responses[] = new Response($code, $headers, $body);
            }

            $mock = new MockHandler($responses);
            $handler = HandlerStack::create($mock);

            return [
                new GuzzleClient(['handler' => $handler]),
            ];
        }, $data);
    }
}
