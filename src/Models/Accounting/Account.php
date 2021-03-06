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
class Account extends BaseModel
{
    /**
     * The API Endpoint
     *
     * If it's null it's a model that would be part of a collecton
     * that has an endpoint
     *
     * @var string $endpoint
     */
    public $endpoint = 'Accounts';

    /**
     * String required to detect name of field used as id
     *
     * @var string $idField
     */
    protected $idField  = 'accountID';

    /**
     * String required to detect if we need to validate model by types
     *
     * @var string $typeField
     *
     * TODO what is going on with this?
     */
    protected $typeField  = 'type';

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
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 10,
            'create' => [
                'exceptType' => 'BANK',
            ],
        ],
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'min' => 0,
            'max' => 150,
            'create' => [
                'required' => true,
            ],
        ],
        // An inconvenient name as we use type
        'type' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'valid' => 'accountTypes',
            'create' => [
                'required' => true,
            ],
        ],
        'bankAccountNumber' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
            // This is *only* required if type is bank
            // This is *only* allowed if type is bank
            'only' => [
                'type' => 'BANK',
                'required' => true,
            ],
            'create' => [
                'onlyType' => 'BANK'
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
            'min' => 0,
            'max' => 4000,
            // This is not allowed if type is bank
            'except' => [
                'type' => 'BANK',
                'required' => false,
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
                'required' => false,
            ],
        ],
        'currencyCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'only' => [
                'type' => 'BANK',
                'required' => false,
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
