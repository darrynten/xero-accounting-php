<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Request;

use DarrynTen\Xero\Exception\ApiException;
use DarrynTen\Xero\Exception\ExceptionMessages;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * RequestHandler Class
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */
class RequestHandler
{
    /**
     * GuzzleHttp Client
     *
     * @var Client $client
     */
    private $client;

    /**
     * The api version
     *
     * API version to connect to
     *
     * TODO version differs per type of api
     *
     * @var string $model
     */
    public $version = '2.0';

    /**
     * The api URL
     *
     * @var string $projectId
     */
    public $endpoint = '//api.xero.com/api.xro';

    /**
     * The api key
     *
     * @var string $key
     */
    private $key;

    /**
     * Xero Token
     *
     * @var string $token
     */
    private $token;

    /**
     * Xero Token type
     *
     * @var string $token
     */
    private $tokenType;

    /**
     * Valid HTTP Verbs for this API
     *
     * @var array $verbs
     */
    private $verbs = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];

    /**
     * Request handler constructor
     *
     * @param array $config The connection config
     */
    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->endpoint = $config['endpoint'];

        $this->client = new Client();
    }

    /**
     * Makes a request using Guzzle
     *
     * @param string $verb The HTTP request verb (GET/POST/etc)
     * @param string $service The api service
     * @param string $method The services method
     * @param array $options Request options
     * @param array $parameters Request parameters
     *
     * @see RequestHandler::request()
     *
     * @return array
     * @throws ApiException
     */
    public function handleRequest(string $method, string $uri, array $options, array $parameters = [])
    {
        if (!in_array($method, $this->verbs)) {
            throw new ApiException('405 Bad HTTP Verb', 405);
        }

        if (!empty($parameters)) {
            if ($method === 'GET') {
                // Send as get params
                foreach ($parameters as $key => $value) {
                    $options['query'][$key] = $value;
                }
            } elseif ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
                // Otherwise send JSON in the body
                $options['headers'] = ['Content-Type' => 'text/xml; charset=UTF8'];
                $options['body'] = $parameters['body'];
            }
        }

        // Let's go
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (RequestException $exception) {
            $this->handleException($exception);
        }

        return json_decode(json_encode(simplexml_load_string((string) $response->getBody())));
    }

    /**
     * Handles all API exceptions, and adds the official exception terms
     * to the message.
     *
     * @param RequestException the original exception
     *
     * @throws ApiException
     */
    private function handleException($exception)
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();

        $title = sprintf(
            '%s: %s - %s',
            $code,
            ExceptionMessages::$strings[$code],
            $message
        );

        throw new ApiException($title, $exception->getCode(), $exception);
    }

    /**
     * Get token for Xero API requests
     *
     * @return string
     */
    private function getAuthToken()
    {
//        // Generate a new token if current is expired or empty
//        if (!$this->token || !$this->tokenType) {
//            $this->getRequestToken();
//        }

        return $this->tokenType . ' ' . $this->token;
    }

    /**
     * Make request to Xero API for the new token
     */
    private function getRequestToken()
    {
        $tokenResponse = $this->handleRequest(
            'POST',
            sprintf('%s/%s/RequestToken', $this->url, $this->version),
            [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]
        );

        $this->tokenExpireTime->modify(
            sprintf('+%s seconds', $tokenResponse['expires_in'])
        );
        $this->token = $tokenResponse['access_token'];
        $this->tokenType = $tokenResponse['token_type'];
    }

    /**
     * Makes a request to Xero
     *
     * @param string $method The API method
     * @param string $path The path
     * @param array $parameters The request parameters
     *
     * @return []
     *
     * @throws ApiException
     */
    public function request(string $verb, string $service, string $method = null, array $parameters = [])
    {
        $options = [
            'headers' => [
                'Authorization' => $this->getAuthToken(),
            ],
        ];

        // We sometimes add a companyId to the URL
        if (isset($this->companyId) && !empty($this->companyId)) {
            $options['query']['CompanyId'] = $this->companyId;
        }

        // We always add the API key to the URL
        $options['query']['apikey'] = $this->key;

        // Append version to the endpoint
        $uri = sprintf(
            '%s/%s/%s/%s/',
            $this->endpoint,
            $this->version,
            $service,
            $method
        );

        return $this->handleRequest(
            $verb,
            $uri,
            $options,
            $parameters
        );
    }
}
