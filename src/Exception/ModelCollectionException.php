<?php
/**
 * SageOne API Exception
 *
 * @category Exception
 * @package  SageOne
 * @author   Vitaliy Likhachev <make.it.git@gmail.com>
 * @license  MIT <https://github.com/darrynten/sage-one-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/sage-one-php
 */

namespace DarrynTen\Xero\Exception;

use Exception;

/**
 * Model collection exception for Xero
 */
class ModelCollectionException extends Exception
{
    const GETTING_UNDEFINED_PROPERTY = 10201;
    const MISSING_REQUIRED_PROPERTY = 10202;

    /**
     * @var array $modelCollectionErrorMessages [102xx codes]
     */
    public static $modelCollectionErrorMessages = [
        10200 => 'Undefined model collection exception',
        10201 => 'Attempting to access undefined property',
        10202 => 'Missing required property in object'
    ];

    /**
     * Custom Model collection exception handler
     *
     * @var integer $code The error code [10200 is default]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 10200, $extra = '')
    {
        $message = sprintf(
            'ModelCollection %s %s',
            self::$modelCollectionErrorMessages[$code],
            $extra
        );

        parent::__construct($message, $code);
    }
}
