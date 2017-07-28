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
    const GETTING_UNDEFINED_PROPERTY = 20201;
    const MISSING_REQUIRED_PROPERTY = 20202;

    /**
     * Custom Model collection exception handler
     *
     * @var integer $code The error code [20200 is default]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 20200, $extra = '')
    {
        $message = sprintf(
            'ModelCollection %s %s',
            $extra,
            ExceptionMessages::$collectionMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
