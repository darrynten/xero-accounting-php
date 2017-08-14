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
    public $entity = 'Contact';

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
            'nullable' => true,
            'readonly' => true,
        ],
        'contactNumber' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
            'min' => 0,
            'max' => 50,
        ],
        'accountNumber' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 50,
        ],
        'contactStatus' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'name' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 255,
        ],
        'firstName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 255,
        ],
        'lastName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 255,
        ],
        'emailAddress' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 0,
            'max' => 255,
        ],
        'skypeUserName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'bankAccountDetails' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
        ],
        'taxNumber' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
            'minLength' => 0,
            'maxLength' => 50,
        ],
        'accountsReceivableTaxType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'accountsPayableTaxType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'addresses' => [
            'type' => 'AddressModel',
            'nullable' => true,
            'readonly' => false,
        ],
        'phones' => [
            'type' => 'Phones',
            'nullable' => true,
            'readonly' => false,
        ],
        'isSupplier' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'isCustomer' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => true,
        ],
        'defaultCurrency' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'UpdatedDateUTC' => [
            'type' => 'DateTime',
            'nullable' => true,
            'readonly' => false,
        ],
        /*
         * The following are only retrieved on GET requests for a single contact or when pagination is used
         */
        'contactPersons' => [
            'type' => 'ContactPersons',
            'nullable' => true,
            'readonly' => false,
        ],
        'XeroNetworkKey' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'salesDefaultAccountCode' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
        ],
        'purchasesDefaultAccountCode' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
        ],
        'salesTrackingCategories' => [
            'type' => 'SalesTrackingCategories',
            'nullable' => true,
            'readonly' => false,
        ],
        'purchasesTrackingCategories' => [
            'type' => 'PurchasesTrackingCategories',
            'nullable' => true,
            'readonly' => false,
        ],
        'trackingCategoryName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        // maybe TrackingOptionName as in mocks ???????
        'trackingCategoryOption' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'paymentTerms' => [
            'type' => 'PaymentTerms',
            'nullable' => true,
            'readonly' => false,
        ],
        'contactGroups' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
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
            'type' => 'BatchPayments',
            'nullable' => true,
            'readonly' => false,
        ],
        'discount' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
        ],
        'hasAttachments' => [
            'type' => 'boolean',
            'nullable' => true,
            'readonly' => false,
        ],
        'contactPerson' => [
            'type' => 'ContactPerson',
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
