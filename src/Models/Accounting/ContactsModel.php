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
use DarrynTen\Xero\Models\Accounting\AddressModel;
use DarrynTen\Xero\Models\Accounting\BatchPaymentsModel;
use DarrynTen\Xero\Models\Accounting\BrandingThemesModel;
use DarrynTen\Xero\Models\Accounting\ContactGroupModel;
use DarrynTen\Xero\Models\Accounting\ContactPersonsModel;
use DarrynTen\Xero\Models\Accounting\PaymentTermsBillsModel;
use DarrynTen\Xero\Models\Accounting\PaymentTermsSalesModel;
use DarrynTen\Xero\Models\Accounting\PhonesModel;
use DarrynTen\Xero\Models\Accounting\PurchasesTrackingCategoriesModel;
use DarrynTen\Xero\Models\Accounting\SalesTrackingCategoryModel;
use DarrynTen\Xero\Models\Accounting\TrackingCategoriesOptionsModel;

/**
 * Contacts Model
 *
 * Details on writable properties for Contacts:
 * https://developer.xero.com/documentation/api/contacts
 */
class ContactsModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Contacts';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'Contact';

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
            'type' => 'ContactPersonsModel',
            'nullable' => true,
            'readonly' => false,
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
            'type' => 'AddressModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'phones' => [
            'type' => 'PhonesModel',
            'nullable' => false,
            'readonly' => false,
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
        'UpdatedDateUTC' => [
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
        'XeroNetworkKey' => [
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
        ],
        'salesTrackingCategories' => [
            'type' => 'SalesTrackingCategoryModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'purchasesTrackingCategories' => [
            'type' => 'PurchasesTrackingCategoriesModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'paymentTerms' => [
            'type' => 'PaymentTermsModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'contactGroups' => [
            'type' => 'ContactGroupModel',
            'nullable' => true,
            'readonly' => false,
        ],
        'website' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'brandingTheme' => [
            'type' => 'BrandingThemeModel',
            'nullable' => true,
            'readonly' => false,
        ],
        'batchPayments' => [
            'type' => 'BatchPaymentsModel',
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
        'contactPerson' => [
            'type' => 'ContactPersonModel',
            'nullable' =>  true,
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