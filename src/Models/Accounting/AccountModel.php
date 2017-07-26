<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Models\Accounting;

use DarrynTen\Xero\BaseModel;

/**
 * Account Model
 *
 * Details on writable properties for Account:
 * https://developer.xero.com/documentation/api/accounts
 */
class AccountModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * If it's null it's a model that would be part of a collecton
     * that has an endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Accounts';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'Account';

    /**
     * Defines all possible fields.
     *
     * Used by the base class to decide what gets submitted in a save call,
     * validation, etc
     *
     * All must include a type, whether or not it's nullable, and whether or
     * not it's readonly.
     *
     * NB: Naming convention for keys is to lowercase the first character of the
     * field returned by Xero (they use PascalCase and we use camelCase)
     *
     * Details on writable properties for Accounts:
     * https://developer.xero.com/documentation/api/accounts
     *
     * @var array $fields
     */
    protected $fields = [
        'accountID' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
        ],
        'code' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => true,
        ],
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
        ],
        // An inconvenient name as we use type
        'type' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'valid' => 'accountTypes',
        ],
        'bankAccountNumber' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
            // This is *only* required if type is bank
            // This is *only* allowed if type is bank
            'only' => [
                'type' => 'BANK',
            ],
        ],
        'status' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'valid' => 'accountStatusCodes',
        ],
        'description' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            // This is not allowed if type is bank
            'except' => [
                'type' => 'BANK',
            ],
        ],
        'bankAccountType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'valid' => 'bankAccountTypes',
            // These are probably not a good idea...
            'only' => [
                'type' => 'BANK',
            ],
        ],
        'currencyCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'only' => [
                'type' => 'BANK',
            ]
        ],
        'taxType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
        ],
        'enablePaymentsToAccount' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'showInExpenseClaims' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'class' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
        ],
        //  Note that non-system accounts may have this element set as either "" or null.
        'systemAccount' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'reportingCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'reportingCodeName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'hasAttachments' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'updatedDateUTC' => [
            'type' => 'DateTime',
            'nullable' => true,
            'readonly' => true,
        ],
    ];

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * @var array $features
     */
    protected $features = [
        'all' => true,
        'get' => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order' => true,
        'filter' => true,
    ];

    // TODO Attachments
}
