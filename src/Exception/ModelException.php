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
    const NO_GET_ALL_SUPPORT = 20101;
    const NO_GET_ONE_SUPPORT = 20102;
    const NO_CREATE_SUPPORT = 20103;
    const NO_DELETE_SUPPORT = 20104;
    const NO_UPDATE_SUPPORT = 20105;
    const NO_SORT_SUPPORT = 20106;
    const NO_FILTER_SUPPORT = 20107;

    // Properies
    const PROPERTY_WITHOUT_CLASS = 20110;
    const NULL_WITHOUT_NULLABLE = 20111;
    const INVALID_LOAD_RESULT_PAYLOAD = 20112;
    const SETTING_UNDEFINED_PROPERTY = 20113;
    const SETTING_READ_ONLY_PROPERTY = 20114;
    const UNEXPECTED_PREPARE_CLASS = 20115;
    const GETTING_UNDEFINED_PROPERTY = 20116;
    const TRYING_SORT_BY_UNKNOWN_FIELD = 20117;
    const TRYING_FILTER_BY_UNKNOWN_FIELD = 20118;

    /**
     * Custom Model exception handler
     *
     * @var string $endpoint The name of the model
     * @var integer $code The error code (as per above) [20100 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($endpoint, $code = 20100, $extra = '')
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
