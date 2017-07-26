<?php
/**
 * Xero
 *
 * @category Base
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero;

use DarrynTen\Xero\Config\ConfigFactory;
use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\AnyCache\AnyCache;

/**
 * Base class for Xero manipulation
 *
 * @package Xero
 */
class Xero
{
    /**
     * Configuration
     *
     * @var Config $config
     */
    public $config;

    /**
     * API Request Handler
     *
     * @var RequestHandler $request
     */
    private $request;

    /**
     * The local cache
     *
     * @var AnyCache $cache
     */
    private $cache;

    /**
     * Xero constructor
     *
     * @param array $config The API client config details
     */
    public function __construct(array $config)
    {
        // $this->config = new Config($config);
        $configFactory = new ConfigFactory();
        $this->config = $configFactory->getConfig($config);
        $this->cache = new AnyCache();
        $this->request = new RequestHandler($this->config->getRequestHandlerConfig());
    }
}
