<?php
/**
 * XeroPHP Config Exception
 *
 * @category Exception
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */

namespace DarrynTen\Xero\Exception;

use Exception;

/**
 * Config exception for XeroPHP
 *
 * @package XeroPHP
 */
class ConfigException extends Exception
{
    const UNDEFINED_CONFIG_EXCEPTION = 20300;
    const MISSING_KEY = 20301;
    const MISSING_APPLICATION_TYPE = 20302;
    const UNKNOWN_APPLICATION_TYPE = 20303;

    /**
     * Custom validation exception handler
     *
     * @var string $endpoint The name of the model
     * @var integer $code The error code (as per above) [11000 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 20300, $extra = '')
    {
        $message = sprintf(
            'Config error %s %s',
            $extra,
            ExceptionMessages::$configErrorMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
