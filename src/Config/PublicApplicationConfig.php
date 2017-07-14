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
 * Public Application Configuration
 *
 * Public applications use the standard 3 legged OAuth process where a user
 * can authorise your application to have access to their Xero organisation.
 * Public applications can either be web based or desktop/mobile installed.
 * Access tokens for public applications expire after 30 minutes.
 * https://developer.xero.com/documentation/auth-and-limits/public-applications
 */
class PublicApplicationConfig extends BaseConfig
{
    public $applicationType = 'public';

    // This is *not* the oauth callback url
    public $callbackDomain;

    public $signWith = 'HMAC-SHA1';
}
