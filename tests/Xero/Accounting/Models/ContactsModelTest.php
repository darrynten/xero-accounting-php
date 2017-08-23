<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

use DarrynTen\Xero\Models\Accounting\Contact;
use DarrynTen\Xero\Models\Accounting\Address;
use DarrynTen\Xero\Tests\Xero\Accounting\BaseAccountingModelTest;
use DarrynTen\Xero\Request\RequestHandler;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Exception\ValidationException;
use DarrynTen\Xero\Validation\ValidationPatterns;

class ContactsModelTest extends BaseAccountingModelTest
/*
 * Using BaseModelTest because AccountingModelTest is made to fit AccountingModel only. E.g. look
 * at verifyInject() method.
 */
{
    public function testInstanceOf()
    {
        $this->verifyInstanceOf(Contact::class);
    }

    public function testSetUndefined()
    {
        $this->verifySetUndefined(Contact::class);
    }

    public function testGetUndefined()
    {
        $this->verifyGetUndefined(Contact::class);
    }

    public function testCanNullify()
    {
        $this->verifyCanNullify(Contact::class, 'skypeUserName');
    }

    // Failed asserting that exception of type "DarrynTen\Xero\Exception\ModelException" is thrown.

/*    public function testBadImport()
    {
        $this->verifyBadImport(Contact::class, '222');
    }*/

    public function testAttributes()
    {
        $this->verifyAttributes(
            Contact::class,
            [
                'contactID' => [
                    'type' => 'string',
                    'nullable' => false,
                    'readonly' => false,
                    'regex' => ValidationPatterns::GUID,
                ],
                /* contactNumber property is missing in the provided Mock. Therefore I assume it is nullable.
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
                    'regex' => ValidationPatterns::ALPHANUMERIC_DASH_UNDERSCORE,
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
                ],
                'phones' => [
                    'type' => 'Phone',
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
                'paymentTerms' => [
                    'type' => 'PaymentTerm',
                    'nullable' => false,
                    'readonly' => false,
                ],
                'contactGroups' => [
                    'type' => 'ContactGroup',
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
                    'type' => 'BatchPayment',
                    'nullable' => false,
                    'readonly' => false,
                    'collection' => true,
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
            ]
        );
    }

    public function testFeatures()
    {
        $this->verifyFeatures(Contact::class, [
            'all' => true,
            'get' => true,
            'delete' => false,
            'create' => true,
            'update' => true,
            'order' => true,
            'filter' => true,
        ]);
    }

    public function testInject()
    {
        $this->verifyInject(Contact::class, function ($model) {
            $this->assertEquals($model->contactID, 'bd2270c3-8706-4c11-9cfb-000b551c3f51');
            $this->assertEquals($model->name, 'ABC Limited');
            $this->assertEquals($model->firstName, 'Andrea');
            $this->assertEquals($model->lastName, 'Dutchess');
            $this->assertEquals($model->emailAddress, 'a.dutchess@abclimited.com');
            $this->assertEquals($model->skypeUserName, 'skype.dutchess@abclimited.com');
            $this->assertEquals($model->bankAccountDetails, 454611121);
            $this->assertEquals($model->taxNumber, 415465456454);
            $this->assertEquals($model->accountsReceivableTaxType, 'INPUT2');
            $this->assertEquals($model->accountsPayableTaxType, 'OUTPUT2');

            // Relationships
            $this->assertInstanceOf(Address::class, $model->addresses->results[0]);
            $this->assertEquals($model->addresses->results[0]->addressType, 'POBOX');

            // TODO
            $objArray = json_decode($model->toJson(), true);
            // TODO write the tests

            $this->assertCount(32, $objArray);
        });
    }
}
