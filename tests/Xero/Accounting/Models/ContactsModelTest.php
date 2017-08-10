<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

use DarrynTen\Xero\Models\Accounting\ContactsModel;
use DarrynTen\Xero\Models\Accounting\AddressModel;
use DarrynTen\Xero\Tests\Xero\Accounting\BaseModelTest;
use DarrynTen\Xero\Request\RequestHandler;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Exception\ValidationException;

class ContactsModelTest extends BaseModelTest
{
    public function testInstanceOf()
    {
        $this->verifyInstanceOf(ContactsModel::class);
    }

    public function testSetUndefined()
    {
        $this->verifySetUndefined(ContactsModel::class);
    }

    public function testGetUndefined()
    {
        $this->verifyGetUndefined(ContactsModel::class);
    }

    public function testCanNullify()
    {
        $this->verifyCanNullify(ContactsModel::class, 'accountNumber');
    }

    // Failed asserting that exception of type "DarrynTen\Xero\Exception\ModelException" is thrown.

/*    public function testBadImport()
    {
        $this->verifyBadImport(ContactsModel::class, 'contactID');
    }*/

    public function testAttributes()
    {
        $this->verifyAttributes(
            ContactsModel::class,
            [
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
            ]
        );
    }

    public function testFeatures()
    {
        $this->verifyFeatures(ContactsModel::class, [
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
        $this->verifyInject(ContactsModel::class, function ($model) {
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

            // TODO
            $objArray = json_decode($model->toJson(), true);
            $this->assertCount(500, $objArray);
        });
    }
/*
    public function testGetByIds()
    {
        $this->verifyGetByIds(
            ContactsModel::class,
            ['297c2dc5-cc47-4afd-8ec8-74990b8761e9', '5040915e-8ce7-4177-8d08-fde416232f18'],
            function ($results) {
                $this->assertEquals(2, count($results));
                $model = $results[0];
                $this->assertEquals($model->accountID, '297c2dc5-cc47-4afd-8ec8-74990b8761e9');
                $this->assertFalse($model->enablePaymentsToAccount);
                $this->assertEquals($model->type, 'BANK');
            }
        );
    }

    public function testCreate()
    {
        $this->verifyCreate(
            ContactsModel::class,
            function ($response) {
                $this->assertEquals(304, $response->code);
                // TODO Do actual checks
            },
            function ($response) {
                $this->assertEquals(304, $response->code);
            }
        );
    }

    public function testUpdate()
    {
        $this->verifyUpdate(
            ContactsModel::class,
            function ($response) {
                $this->assertEquals(200, $response->code);
                // TODO Do actual checks
            },
            function ($response) {
                $this->assertEquals(200, $response->code);
            }
        );
    }

    public function testDelete()
    {
        $this->verifyDelete(ContactsModel::class, 11, function () {
            // TODO do actual checks
        });
    }*/
}
