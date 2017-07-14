<?php

namespace DarrynTen\Xero\Config;

use DarrynTen\Xero\Exception\ApiException;

/**
 * Xero Config
 *
 * @category Configuration
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */
abstract class BaseConfig
{
    /**
     * Xero key
     *
     * "Consumer Key"
     *
     * You will need to generate a public/private key-pair, of which the
     * public part will be uploaded to Xero during application registration.
     *
     * @var string $key
     */
    private $key = null;

    /**
     * Shared secret
     *
     * "Consumer Secret"
     *
     * The consumer secret is not used for private/partner apps.
     *
     * @var string $secret
     */
    private $secret = null;

    /**
     * The api versions
     *
     * API version to connect to, depending on service
     *
     * @var array $versions
     */
    public $versions = [
        'accounting' => '2.0',
        'payroll' => '1.0',
        'files' => '1.0',
    ];

    /**
     * The project ID
     *
     * @var string $projectId
     */
    public $endpoint = '//api.xero.com/api.xro';

    /**
     * Whether or not to use caching.
     *
     * The default is true as this is a good idea.
     *
     * @var boolean $cache
     */
    public $cache = true;

    /**
     * Rate Limit
     *
     * Minute Limit: 60 calls in a rolling 60 second window
     * Daily Limit: 5000 calls in a rolling 24 hour window
     *
     * NB this is only checking the day one, we need to handle the
     * minute one too TODO
     *
     * https://developer.xero.com/documentation/auth-and-limits/xero-api-limits
     *
     * @var int $rateLimit
     */
    public $rateLimit = 5000;

    /**
     * Rate Limit Period (in seconds)
     *
     * 1 day = 60 * 60 * 24 = 86400
     *
     * @var int $rateLimitPeriod
     */
    public $rateLimitPeriod = 86400;

    /**
     * The number of times to retry failed calls
     *
     * @var integer $retries
     */
    public $retries = 3;

    /**
     * The request size limit for POST in Accounting and Payroll
     * in kilobytes
     *
     * Note: When posting form-encoded xml to the API, the encoded data
     * will be approx 50% larger than the original xml message.
     *
     * You can batch elements in bundles up to 50
     *
     * 3.5MB
     *
     * @var integer
     */
    public $maxPostSize = 35000;

    /**
     * The request size limit for POST to Files API
     * in kilobytes
     *
     * 10MB
     *
     * @var integer $maxFileSize
     */
    public $maxFileSize = 100000;

    /**
     * Application type
     *
     * https://developer.xero.com/documentation/getting-started/api-application-types
     *
     * Xero offers 3 types of application
     *
     * Private, Public, and Partner
     *
     * Private applications use 2 legged OAuth and bypass the user authorization
     * workflow in the standard OAuth process. Private applications are linked
     * to a single Xero organisation which is chosen when you register your
     * application. Access tokens for private applications don’t expire unless
     * the application is deleted or disconnected from within the Xero
     * organisation.
     * https://developer.xero.com/documentation/auth-and-limits/private-applications
     *
     * Public applications use the standard 3 legged OAuth process where a user
     * can authorise your application to have access to their Xero organisation.
     * Public applications can either be web based or desktop/mobile installed.
     * Access tokens for public applications expire after 30 minutes.
     * https://developer.xero.com/documentation/auth-and-limits/public-applications
     *
     * Partner applications are public applications that have been upgraded to
     * support long term access tokens.
     *
     * Partner applications use the same 3-legged authorization process as
     * public applications, but the 30-minute access tokens can be renewed.
     * Access tokens can be renewed without further user authorization. This
     * process of token renewal can occur indefinitely, while the partner
     * application is in active use.
     *
     * Partner applications also use a different signature method to public
     * apps. You need to sign your requests using the RSA-SHA1 method. More
     * details:
     * https://developer.xero.com/documentation/auth-and-limits/partner-applications
     *
     * They all have different authentication rules.
     *
     * See their individual config classes for more info
     *
     * TODO perhaps call this authenticationType?
     *
     * @var string $applicationType
     */
    public $applicationType;

    // Gets sent through as user-agent apparently
    // https://github.com/XeroAPI/XeroOAuth-PHP/blob/master/_config.php#L24
    public $applicationName;

    /**
     * Callback URL for auth
     *
     * https://developer.xero.com/documentation/auth-and-limits/oauth-callback-domains-explained
     *
     * @var string $callbackUrl
     */
    public $callbackUrl;

    /**
     * Construct the config object
     *
     * @param array $config An array of configuration options
     */
    public function __construct($config)
    {
        // Throw exceptions on essentials
        $this->checkAndSetEssentials($config);

        // optionals
        $this->checkAndSetOverrides($config);
    }

    /**
     * Check and set essential configuration elements.
     *
     * Required:
     *
     *   - API Key
     *   - Username
     *   - Password
     *
     * @param array $config An array of configuration options
     */
    private function checkAndSetEssentials($config)
    {
        if (!isset($config['username']) || empty($config['username'])) {
            throw new ApiException('Missing username');
        }
        if (!isset($config['password']) || empty($config['password'])) {
            throw new ApiException('Missing password');
        }
        if (!isset($config['key']) || empty($config['key'])) {
            throw new ApiException('Missing API key');
        }

        $this->username = (string)$config['username'];
        $this->password = (string)$config['password'];
        $this->key = (string)$config['key'];
    }

    /**
     * Check and set any overriding elements.
     *
     * Optionals:
     *
     *   - Version
     *   - Endpoint
     *   - Caching
     *   - Rate Limit
     *   - Rate Limit Window
     *   - Error Retries
     *
     * @param array $config An array of configuration options
     */
    private function checkAndSetOverrides($config)
    {
        if (isset($config['companyId']) && !empty($config['companyId'])) {
            $this->companyId = (string)$config['companyId'];
        }

        if (isset($config['version']) && !empty($config['version'])) {
            $this->version = (string)$config['version'];
        }

        if (isset($config['endpoint']) && !empty($config['endpoint'])) {
            $this->endpoint = (string)$config['endpoint'];
        }

        if (isset($config['cache'])) {
            $this->cache = (bool)$config['cache'];
        }

        /**
         * TODO
         *
        if (isset($config['rate_limit']) && !empty($config['rate_limit'])) {
            $this->rateLimit = (int)$config['rateLimit'];
        }

        if (isset($config['rate_limit_period']) && !empty($config['rate_limit_period'])) {
            $this->rateLimitPeriod = (int)$config['rateLimitPeriod'];
        }

        if (isset($config['retries']) && !empty($config['retries'])) {
            $this->retries = (int)$config['retries'];
        }
        *
         */
    }

    /**
     * Retrieves the expected config for the API
     *
     * @return array
     */
    public function getRequestHandlerConfig()
    {
        $config = [
            'key' => $this->key,
            'username' => $this->username,
            'password' => $this->password,
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'companyId' => $this->companyId,
        ];

        return $config;
    }
}
