<?php
/**
 * Xero API Exception
 *
 * @category Exception
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Exception;

use Exception;
use DarrynTen\Xero\Exception\ExceptionMessages;

/**
 * Validation exception for Xero
 *
 * @package Xero
 */
class ValidationException extends Exception
{
    const INTEGER_OUT_OF_RANGE = 20301;
    const STRING_LENGTH_OUT_OF_RANGE = 20302;
    const STRING_REGEX_MISMATCH = 20303;
    const VALIDATION_TYPE_ERROR = 20304;

    /**
     * Custom Model exception handler
     *
     * @var integer $code The error code (as per above) [20300 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 20300, $extra = '')
    {
        $message = sprintf(
            'Validation error %s %s',
            $extra,
            ExceptionMessages::$validationMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
