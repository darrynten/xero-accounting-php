<?php
/**
 * Xero Library Exception
 *
 * @category Exception
 * @package  Xero
 * @author   Fergus Strangways-Dixon <fergusdixon@github.com>
 * @license  MIT <https://github.com/darrynten/sage-one-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/sage-one-php
 */

namespace DarrynTen\Xero\Exception;

use Exception;
use DarrynTen\Xero\Exception\ExceptionMessages;

/**
 * Library exception for Xero
 *
 * @package Xero
 */
class LibraryException extends Exception
{
    const METHOD_NOT_IMPLEMENTED = 20501;

    /**
     * Custom NotYetImplemented exception handler
     * @var int $code default code [10300]
     * @var string $address should contain address.
     */
    public function __construct($code = 20500, $address = '')
    {
        $message = sprintf(
            'Error, "%s" %s.',
            $address,
            ExceptionMessages::$libraryErrorMessages[$code]
        );
        parent::__construct($message, $code);
    }
}
