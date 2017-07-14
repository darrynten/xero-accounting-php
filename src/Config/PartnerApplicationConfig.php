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

namespace DarrynTen\Xero\Config;

use DarrynTen\Xero\Config\BaseConfig;

/**
 * Parner Application Configuration
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
 */
class PartnerApplicationConfig extends BaseConfig
{
    public $applicationType = 'partner';

    // paths to certs
    public $privateKey;
    public $publicKey;

    // NB notice the plural, partner apps can have multiple domains
    public $callbackDomains = [];

    public $signWith = 'RSA-SHA1';

    // 30 min = 60 * 30 = 1800 seconds
    public $tokenExpiration = 1800;
}
