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

/**
 * Custom exception for Xero
 *
 * @package Xero
 */
class RequestHandlerException extends Exception
{
    const MALFORMED_REQUEST = 400;
    const USER_AUTHENTICATION_ERROR = 401;
    const ENTITY_NOT_FOUND = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const SCHEDULED_MAINTENANCE = 503;

    /**
     * @inheritdoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        // Construct message from JSON if required.
        if (preg_match('/^[\[\{]\"/', $message)) {
            $messageObject = json_decode($message);
            $message = sprintf(
                '%s: %s - %s',
                $messageObject->status,
                $messageObject->title,
                $messageObject->detail
            );
            if (!empty($messageObject->errors)) {
                $message .= ' - errors: ' . json_encode($messageObject->errors);
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
