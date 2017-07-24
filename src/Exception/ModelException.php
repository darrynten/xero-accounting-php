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
 * Model exception for Xero
 *
 * @package Xero
 */
class ModelException extends Exception
{
    // Methods
    const NO_GET_ALL_SUPPORT = 10101;
    const NO_GET_ONE_SUPPORT = 10102;
    const NO_SAVE_SUPPORT = 10103;
    const NO_DELETE_SUPPORT = 10104;
    const NO_UPDATE_SUPPORT = 10105;
    const NO_SORT_SUPPORT = 10106;
    const NO_FILTER_SUPPORT = 10107;

    // Properies
    const PROPERTY_WITHOUT_CLASS = 10110;
    const NULL_WITHOUT_NULLABLE = 10111;
    const INVALID_LOAD_RESULT_PAYLOAD = 10112;
    const SETTING_UNDEFINED_PROPERTY = 10113;
    const SETTING_READ_ONLY_PROPERTY = 10114;
    const UNEXPECTED_PREPARE_CLASS = 10115;
    const GETTING_UNDEFINED_PROPERTY = 10116;
    const TRYING_SORT_BY_UNKNOWN_FIELD = 10117;
    const TRYING_FILTER_BY_UNKNOWN_FIELD = 10118;

    /**
     * Custom Model exception handler
     *
     * @var string $endpoint The name of the model
     * @var integer $code The error code (as per above) [10100 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($endpoint, $code = 10100, $extra = '')
    {
        $message = sprintf(
            'Model "%s" %s %s',
            $endpoint,
            $extra,
            ExceptionMessages::$modelErrorMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
