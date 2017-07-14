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
    protected $endpoint = 'Accounts';

    protected $accountTypes = [
        'BANK' => 'Bank account',
        'CURRENT' => 'Current Asset account',
        'CURRLIAB' => 'Current Liability account',
        'DEPRECIATN' => 'Depreciation account',
        'DIRECTCOSTS' => 'Direct Costs account',
        'EQUITY' => 'Equity account',
        'EXPENSE' => 'Expense account',
        'FIXED' => 'Fixed Asset account',
        'INVENTORY' => 'Inventory Asset account',
        'LIABILITY' => 'Liability account',
        'NONCURRENT' => 'Non-current Asset account',
        'OTHERINCOME' => 'Other Income account',
        'OVERHEADS' => 'Overhead account',
        'PREPAYMENT' => 'Prepayment account',
        'REVENUE' => 'Revenue account',
        'SALES' => 'Sale account',
        'TERMLIAB' => 'Non-current Liability account',
        'PAYGLIABILITY' => 'PAYG Liability account',
        'SUPERANNUATIONEXPENSE' => 'Superannuation Expense account',
        'SUPERANNUATIONLIABILITY' => 'Superannuation Liability account',
        'WAGESEXPENSE' => 'Wages Expense account',
        'WAGESPAYABLELIABILITY' => 'Wages Payable Liability account',
    ];

    protected $accountStatusCodes = [
        'ACTIVE' => 'Active account',
        'ARCHIVED' => 'Archived account',
    ];

    protected $bankAccountTypes = [
        'BANK' => 'Bank account',
        'CREDITCARD' => 'Credit card account',
        'PAYPAL' => 'Paypal account',
    ];

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
        'accountId' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
        ],
        'type' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'types' => 'accountTypes',
        ],
        'bankAccountNumber' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
            // This is *only* required if type is bank
            // This is *only* allowed if type is bank
            'required' => true,
            'only' => [
                'type' => 'BANK',
            ],
        ],
        'status' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => true,
            'types' => 'accountStatusCodes'
        ],
        'description' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            // This is not allowed if type is bank
            'except' => [
                'type' => 'BANK',
            ],
        ],
        'bankAccountType' => [
            'type' => 'BankAccountType',
            'nullable' => true,
            'readonly' => false,
            'types' => 'bankAccountTypes',
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
            'type' => 'TaxType',
            'nullable' => false,
            'readonly' => true,
        ],
        'enablePaymentsToAccount' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'showInExpenseClaims' => [
            'type' => 'boolean',
            'nullable' => false,
            'readonly' => true,
        ],
        'class' => [
            'type' => 'AccountClass',
            'nullable' => false,
            'readonly' => true,
        ],
        //  Note that non-system accounts may have this element set as either "" or null.
        'systemAccount' => [
            'type' => 'boolean',
            'nullable' => false,
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
            'nullable' => false,
            'readonly' => true,
        ],
        'updatedDateUTC' => [
            'type' => 'DateTime',
            'nullable' => false,
            'readonly' => true,
        ],
        // Not sure how to deal with these
        'where' => '',
        'order' => '',
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
        /**
         * Non-system accounts and accounts not used on transactions
         * can be deleted using the delete method. If an account is
         * not able to be deleted you can update the status to ARCHIVED
         */
        'delete' => true,
    ];

    // TODO Attachments
}
