<?php

namespace SynergiTech\Creditsafe;

use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token;
use GuzzleHttp\Psr7;

/**
 * Client Class used to store data relating to the client created
 */
class Client
{

    protected $http_client;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Service\CountryService
     */
    protected $countries;

    /**
     * @var Service\CompanyService
     */
    protected $company;
    /**
     * @var Service\PortfolioService
     */
    protected $portfolio;
    /**
     * @var Service\CompanyEventService
     */
    protected $monitor;

    /**
     * construct function that builds the client class
     * @param array $config creditsafe configuration
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);

        if (isset($this->config['http_client'])) {
            $this->http_client = $this->config['http_client'];
            unset($this->config['http_client']);
        } else {
            $this->http_client = new \GuzzleHttp\Client([
                'base_uri' => $this->getBaseURL(),
            ]);
        }
    }

    /**
     * This function is used to authenticate a username and password
     * @return void
     * @throws Exception\Unauthorized if the provided authentication details are not valid
     */
    public function authenticate() : void
    {
        try {
            $authenticate = $this->http_client->request('POST', 'authenticate', [
                'json'=> [
                    'username'=> $this->config['username'],
                    'password' => $this->config['password']
                ]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $message = 'There was a problem authenticating with the Creditsafe API';
            $code = 400;
            if ($e->hasResponse()) {
                $body = $e->getResponse()->getBody();
                $details = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($details['message'])) {
                    $message = $details['message'];
                }
                $code = $e->getResponse()->getStatusCode();
            }
            throw new Exception\Unauthorized($message, $code, $e);
        }
        $decode = json_decode((string) $authenticate->getBody(), true);
        $this->setToken($decode['token']);
    }

    /**
     * set token
     * @param string $token Token must be set to have access
     *  to the api and is only valid for an hour
     * @return void
     */
    public function setToken(string $token) : void
    {
        $this->token = (new Parser(new JoseEncoder()))->parse($token);
    }

    /**
     * @return Token
     */
    public function getToken() : Token
    {
        return $this->token;
    }

    /**
     * Checks if token is valid
     * @return void
     */
    public function checkToken() : void
    {
        /**
         * argument required as of lcobucci/jwt 3.4+
         * https://github.com/lcobucci/jwt/commit/2cfa548b3f26176ab3e56a1ff3503b4d0db7ae7c
         * https://github.com/lcobucci/jwt/issues/550#issuecomment-733557709
         */
        if ($this->token === null || $this->token->isExpired(new \DateTimeImmutable())) {
            $this->authenticate();
        }
    }

    /**
     * This request function handles all requests for the api
     * @param  string $type     Sets the type of HTTP Request e.g GET ,POST
     * @param  string $endpoint Sets the endpoint
     * @param  array  $params   Sets params for a endpoint
     * @return array    Returns the results of the endpoint
     */
    public function request(string $type, string $endpoint, array $params = []) : array
    {

        $this->checkToken();

        $guzzleArgs =  [
            'headers' => [
                'Authorization' => (string) $this->token->toString()
            ],
        ];

        if ($type == 'GET') {
            $guzzleArgs['query'] = $params;
        } else if($type == 'POST' || $type == 'PUT'){
            $guzzleArgs['json'] = $params;
        }

        try {
            $res = $this->http_client->request($type, $endpoint, $guzzleArgs);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $message = $e->getMessage();
            $correlationID = null;

            if ($e->hasResponse()) {
                $body = $e->getResponse()->getBody();
                $details = json_decode($body, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $apiMessage = [];
                    if (isset($details['message'])) {
                        $apiMessage[] = $details['message'];
                    }
                    if (isset($details['details'])) {
                        $apiMessage[] = $details['details'];
                    }
                    if (!empty($apiMessage)) {
                        $message = implode(': ', $apiMessage);
                    }
                    if (isset($details['correlationId'])) {
                        $correlationID = $details['correlationId'];
                    }
                }
            }

            $exception = new Exception\APIException($message, 400, $e);
            if ($correlationID) {
                $exception->setCorrelationID($correlationID);
            }
            throw $exception;
        }

        $res = (array)json_decode((string) $res->getBody(), true);


        return $res;
    }

    /**
     *  A function that handles the creation of a GET  Request
     * @param  string $endpoint  An endpoint used to create an request
     * @param  array  $params   Sets params for a endpoint
     * @return array  Returns the results of the endpoint
     */
    public function get(string $endpoint, array $params = []) : array
    {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     *  A function that handles the creation of a POST  Request
     * @param  string $endpoint  An endpoint used to create a request
     * @param  array  $params   Sets params for an endpoint
     * @return array  Returns the results of the endpoint
     */
    public function post(string $endpoint, array $params = []) : array
    {
        return $this->request('POST', $endpoint, $params);
    }

    /**
     *  A function that handles the creation of a DELETE  Request
     * @param  string $endpoint  An endpoint used to create a request
     * @param  array  $params   Sets params for an endpoint
     * @return array  Returns the results of the endpoint
     */
    public function delete(string $endpoint, array $params = []) : array
    {
        return $this->request('DELETE', $endpoint, $params);
    }

    /**
     * Get Company Events
     * @return Service\CompanyEventService Returns Company Events
     */
    public function monitoring() : Service\CompanyEventService
    {
        if (!isset($this->monitor)) {
            $this->monitor = new Service\CompanyEventService($this);
        }
        return $this->monitor;
    }

    /**
     *  Get company services
     * @return Service\CompanyService Returns Company Services
     */
    public function companies() : Service\CompanyService
    {
        if (!isset($this->company)) {
            $this->company = new Service\CompanyService($this);
        }
        return $this->company;
    }

    /**
     *  Get Countries
     * @return Service\CountryService  Returns the Countries
     */
    public function countries() : Service\CountryService
    {
        if (!isset($this->countries)) {
            $this->countries = new Service\CountryService($this);
        }
        return $this->countries;
    }

    /**
     *  Get portfolio services
     * @return Service\PortfolioService Returns Portfolio Services
     */
    public function portfolio() : Service\PortfolioService
    {
        if (!isset($this->portfolio)) {
            $this->portfolio = new Service\PortfolioService($this);
        }
        return $this->portfolio;
    }

    /**
     * @return array
     */
    public function getDefaultConfig() : array
    {
        return [
            'apiURI' => \config('creditsafe.api_uri'),
            'apiVersion' => \config('creditsafe.api_version'),
        ];
    }

    /**
     *  Function gets the base url for the api by concatenating the API URL and the API version
     * @return string Returns a string containing the base url
     */
    public function getBaseURL() : string
    {
        return $this->getApiURL().$this->getApiVersion().'/';
    }

    /**
     * Function gets the Api url
     * @return string Returns a string containing the api url
     */
    protected function getApiURL() : string
    {
        return $this->config['apiURI'];
    }

    /**
     * Function gets the Api Version
     * @return string Returns a string containing the Api Version
     */
    protected function getApiVersion() : string
    {
        return $this->config['apiVersion'];
    }
}
