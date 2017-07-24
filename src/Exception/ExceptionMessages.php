<?php

namespace DarrynTen\Xero\Exception;

/**
 * Exception message strings for the ApiException object that gets throws.
 *
 * Maps to the Xero docs.
 */
class ExceptionMessages
{
    // Model codes 101xx
    public static $modelErrorMessages = [
        // Methods
        10100 => 'Undefined model exception',
        10101 => 'Get all is not supported',
        10102 => 'Get single is not supported',
        10103 => 'Save is not supported',
        10104 => 'Delete is not supported',
        10105 => 'Update is not supported',
        10106 => 'Sort is not supported',
        10107 => 'Filter is not supported',
        // Properties
        10110 => 'Property is referencing an undefined, non-primitive class',
        10111 => 'Property is null without nullable permission',
        10112 => 'A property is missing in the loadResult payload',
        10113 => 'Attempting to set a property that is not defined in the model',
        10114 => 'Attempting to set a read-only property',
        10115 => 'Unexpected class encountered while preparing row',
        10116 => 'Attempting to get an undefined property',
        10117 => 'Unknown property for sorting',
        10118 => 'Unknown property for filtering',
    ];

    // Validation codes 1012x
    public static $validationMessages = [
        10200 => 'Unknown validation error',
        10201 => 'Integer value is out of range',
        10202 => 'String length is out of range',
        10203 => 'String did not match validation regex',
        10204 => 'Validation type is invalid',
    ];

    // Maps to standard HTTP error codes
    public static $strings = [
        400 => '400',
        401 => '401',
        402 => '402',
        404 => '404',
        405 => '405',
        409 => '409',
        415 => '415',
        429 => '429',
        500 => '500',
        503 => '503',
    ];
}
