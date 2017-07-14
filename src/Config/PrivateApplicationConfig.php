<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/sage-one-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/sage-one-php
 */

namespace DarrynTen\Xero\Config;

use DarrynTen\Xero\Config\BaseConfig;

/**
 * Private Application Configuration
 *
 * Private applications use 2 legged OAuth and bypass the user authorization
 * workflow in the standard OAuth process. Private applications are linked
 * to a single Xero organisation which is chosen when you register your
 * application. Access tokens for private applications don’t expire unless
 * the application is deleted or disconnected from within the Xero
 * organisation.
 * https://developer.xero.com/documentation/auth-and-limits/private-applications
 *
 */
class PrivateApplicationConfig extends BaseConfig
{
    public $applicationType = 'private';

    // paths to certs
    public $privateKey;
    public $publicKey;

    // Does not expire
    public $tokenExpiration = 0;

    public $signWith = 'RSA-SHA1';
}
