<?php

namespace DarrynTen\Xero\Exception;

/**
 * Exception message strings for the ApiException object that gets throws.
 *
 * Maps to the Xero docs.
 */
class ExceptionMessages
{
    // Model codes 201xx
    public static $modelErrorMessages = [
        // Methods
        20100 => 'Undefined model exception',
        20101 => 'Get all is not supported',
        20102 => 'Get single is not supported',
        20103 => 'Save is not supported',
        20104 => 'Delete is not supported',
        20105 => 'Update is not supported',
        20106 => 'Sort is not supported',
        20107 => 'Filter is not supported',
        // Properties
        20110 => 'Property is referencing an undefined, non-primitive class',
        20111 => 'Property is null without nullable permission',
        20112 => 'A property is missing in the loadResult payload',
        20113 => 'Attempting to set a property that is not defined in the model',
        20114 => 'Attempting to set a read-only property',
        20115 => 'Unexpected class encountered while preparing row',
        20116 => 'Attempting to get an undefined property',
        20117 => 'Unknown property for sorting',
        20118 => 'Unknown property for filtering',
    ];

    // Collections are 202xx
    public static $collectionMessages = [
        20200 => 'Undefined model collection exception',
        20201 => 'Attempting to access undefined property',
        20202 => 'Missing required property in object'
    ];

    // Validation codes 203xx
    public static $validationMessages = [
        20300 => 'Unknown validation error',
        20301 => 'Integer value is out of range',
        20302 => 'String length is out of range',
        20303 => 'String did not match validation regex',
        20304 => 'Validation type is invalid',
    ];

    // Validation codes 204xx
    public static $configErrorMessages = [
        20400 => 'Undefined config exception',
        20401 => 'Missing application key',
        20402 => 'Missing application type',
        20403 => 'Unknown application type',
        20404 => 'Non-Private app but secret is missing',
        20405 => 'Missing application name',
        20406 => 'Missing callback url',
    ];

    public static $libraryErrorMessages = [
        20500 => 'Library Error',
        20501 => 'Method not yet implemented. This still needs to be added, '
               . 'please consider contributing to the project.',
    ];

    // Maps to standard HTTP error codes
    public static $strings = [
        400 => 'A malformed request was sent through or when a validation rule '
             . 'failed. Validation messages are returned in the response body.',
        401 => 'The user is not correctly authenticated and the call requires '
             . 'authentication. The user does not have access rights for this method.',
        404 => 'The requested entity was not found. Entities are bound to '
             . 'companies. Ensure the entity belongs to the company.',
        500 => 'Internal server error',
        501 => 'The method you have called is not implemented',
        503 => 'The service is down for scheduled maintenance or '
             . 'you have exceeded your API rate limit',
    ];
}
