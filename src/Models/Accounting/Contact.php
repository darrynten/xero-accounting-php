<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Models\Accounting;

use DarrynTen\Xero\BaseModel;

/**
 * Contacts Model
 *
 * Details on writable properties for Contacts:
 * https://developer.xero.com/documentation/api/contacts
 */
class Contact extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    public $endpoint = 'Contacts';

    /**
     * String required to detect name of field used as id
     *
     * @var string $idField
     */
    protected $idField  = 'contactID';

    /**
     *
     * Details on writable properties for Contacts:
     * https://developer.xero.com/documentation/api/contact
     *
     * @var array $fields
     */
    protected $fields = [
        'contactID' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'regex' => '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
        ],
        /*
         * contactNumber property is missing in the provided Mock. Therefore I assume it is nullable.
         * Contrary, the property description in XML tables do not have indication that the field is nullable.
         * We need to contact Xero to clarify the question.
         */
        'contactNumber' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
            'min' => 1,
            'max' => 50,
        ],
        /*
         * accountNumber property is missing in the provided Mock. Therefore I assume it is nullable.
         * Contrary, the property description in XML tables do not have indication that the field is nullable.
         * We need to contact Xero to clarify the question.
         */
        'accountNumber' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            // the min/max fields defined in online docs but not in XeroAPI-schemas
            'min' => 1,
            'max' => 50,
        ],
        'contactStatus' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'firstName' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 255,
        ],
        'lastName' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 255,
        ],
        'emailAddress' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            // the max field defined in online docs = 255, in XeroAPI-schemas 500
            'min' => 1,
            'max' => 500,
        ],
        'skypeUserName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            // the min/max fields  not defined in online docs, but not defined in XeroAPI-schemas
            'min' => 1,
            'max' => 50,
        ],
        'contactPersons' => [
            'type' => 'ContactPerson',
            'nullable' => true,
            'readonly' => false,
            'collection' => true,
        ],
        'bankAccountDetails' => [
            'type' => 'integer',
            'nullable' => false,
            'readonly' => false,
        ],
        'taxNumber' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'regex' => '/^[0-9a-zA-Z-_]+$/',
            'min' => 1,
            'max' => 50,
        ],
        'accountsReceivableTaxType' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'accountsPayableTaxType' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'addresses' => [
            'type' => 'Address',
            'nullable' => false,
            'readonly' => false,
            'collection' => true,
        ],
        'phones' => [
            'type' => 'Phone',
            'nullable' => false,
            'readonly' => false,
            'collection' => true,
        ],
        'isSupplier' => [
            'type' => 'boolean',
            'nullable' => false,
            'readonly' => false,
        ],
        'isCustomer' => [
            'type' => 'boolean',
            'nullable' => false,
            'readonly' => false,
        ],
        // https://github.com/XeroAPI/XeroAPI-Schemas/blob/master/src/main/resources/XeroSchemas/v2.00/CurrencyCode.xsd
        // do we need a model for this?
        // in the XMLSchemas type for defaultCurrency is defined as CurrencyCode
        'defaultCurrency' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'updatedDateUTC' => [
            'type' => 'DateTime',
            'nullable' => false,
            'readonly' => false,
        ],
        /*
         * The following are only retrieved on GET requests for a single contact or when pagination is used
         */
        /*
         * XeroNetworkKey property is missing in the provided Mock. Therefore I assume it is nullable.
         * Contrary, the property description in XML tables do not have indication that the field is nullable.
         * We need to contact Xero to clarify the question.
         */
        'xeroNetworkKey' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'salesDefaultAccountCode' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'purchasesDefaultAccountCode' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 50,
            // TODO valid codes
        ],
        'salesTrackingCategories' => [
            'type' => 'SalesTrackingCategory',
            'nullable' => false,
            'readonly' => false,
        ],
        'purchasesTrackingCategories' => [
            'type' => 'PurchasesTrackingCategory',
            'nullable' => false,
            'readonly' => false,
        ],
        // TODO this requires exactly 2 specific ones
        // https://developer.xero.com/documentation/api/contacts
        // how?
        'paymentTerms' => [
            'type' => 'PaymentTerm',
            'nullable' => false,
            'readonly' => false,
        ],
        'contactGroups' => [
            'type' => 'ContactGroup',
            'nullable' => true,
            'readonly' => false,
            'collection' => true,
        ],
        'website' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'brandingTheme' => [
            'type' => 'BrandingTheme',
            'nullable' => true,
            'readonly' => false,
        ],
        'batchPayments' => [
            'type' => 'BatchPayment',
            'nullable' => false,
            'readonly' => false,
        ],
        /*
         * discount property is missing in the provided Mock. Therefore I assume it is nullable.
         * Contrary, the property description in XML tables do not have indication that the field is nullable.
         * We need to contact Xero to clarify the question.
         */
        'discount' => [
            'type' => 'double',
            'nullable' => true,
            'readonly' => false,
        ],
        /*
         * hasAttachments property is missing in the provided Mock. Therefore I assume it is nullable.
         * Contrary, the property description in XML tables do not have indication that the field is nullable.
         * We need to contact Xero to clarify the question.
         */
        'hasAttachments' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => false,
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
        'delete' => false,
        'order' => true,
        'filter' => true,
    ];

    // TODO Attachments
}
