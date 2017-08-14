<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Models\Accounting\Account;
use DarrynTen\Xero\Tests\Xero\Accounting\BaseAccountingModelTest;
use DarrynTen\Xero\Request\RequestHandler;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;

class AccountsModelTest extends BaseAccountingModelTest
{
    public function testInstanceOf()
    {
        $this->verifyInstanceOf(Account::class);
    }

    public function testSetUndefined()
    {
        $this->verifySetUndefined(Account::class);
    }

    public function testGetUndefined()
    {
        $this->verifyGetUndefined(Account::class);
    }

    public function testCanNotNullify()
    {
        $this->verifyCanNotNullify(Account::class, 'type');
    }

    public function testCanNullify()
    {
        $this->verifyCanNullify(Account::class, 'bankAccountNumber');
    }

    public function testBadImport()
    {
        $this->verifyBadImport(Account::class, 'name');
    }

    public function testNotSupportedAll()
    {
        $this->verifyNotSupportedAll(Account::class);
    }

    public function testNotSupportedGet()
    {
        $this->verifyNotSupportedGet(Account::class);
    }

    public function testNotSupportedGetByIds()
    {
        $this->verifyNotSupportedGetByIds(Account::class);
    }

    public function testNotSupportedDelete()
    {
        $this->verifyNotSupportedDelete(Account::class);
    }

    public function testNotSupportedCreate()
    {
        $this->verifyNotSupportedCreate(Account::class);
    }

    public function testNotSupportedUpdate()
    {
        $this->verifyNotSupportedUpdate(Account::class);
    }

    public function testNullWithoutNullableAtribute()
    {
        $this->verifyCantBeNull(Account::class);
    }

    public function testWriteWithReadOnly()
    {
        $this->verifyCantBeWritten(Account::class);
    }

    public function testNotSupportedFilter()
    {
        $this->verifyNotSupportedFilter(Account::class);
    }

    public function testNotSupportedOrder()
    {
        $this->verifyNotSupportedOrder(Account::class);
    }

    public function testFilterByUnknownValue()
    {
        $this->verifyFilterByUnknownValue(Account::class);
    }

    public function testOrderWithWrongParameters()
    {
        $this->verifyOrderWithWrongParameters(Account::class);
    }

    public function testOrderWithUnknownField()
    {
        $this->verifyOrderWithUnknownField(Account::class);
    }

    public function testIdMissingOnCreate()
    {
        $this->verifyIdMissingOnCreate(Account::class);
    }

    public function testMissingRequiredProperty()
    {
        $this->verifyMissingRequiredProperty(Account::class);
    }

    public function testInject()
    {
        $this->verifyInject(Account::class, function ($model) {
            $this->assertEquals($model->accountID, '297c2dc5-cc47-4afd-8ec8-74990b8761e9');
            $this->assertEquals($model->name, 'BNZ Cheque Account');

            // TODO
            $objArray = json_decode($model->toJson(), true);
            $this->assertCount(18, $objArray);
        });
    }

    public function testAttributes()
    {
        $this->verifyAttributes(
            Account::class,
            [
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
            ]
        );
    }

    public function testDefaultValueGet()
    {
        $fields = [
            'accountID' => [
                'default' => 'some'
            ]
        ];

        $model = $this->injectPropertyInModel(Account::class, 'fields', $fields);

        $this->assertEquals('some', $model->accountID);
    }

    public function testFeatures()
    {
        $this->verifyFeatures(Account::class, [
            'all' => true,
            'get' => true,
            'delete' => true,
            'create' => true,
            'update' => true,
            'order' => true,
            'filter' => true,
        ]);
    }

    public function testGetAll()
    {
        $this->verifyGetAll(Account::class, function ($collectionModel) {
            $this->assertEquals(ModelCollection::class, get_class($collectionModel));
            $this->assertEquals(2, $collectionModel->totalResults);
            $this->assertEquals(2, $collectionModel->returnedResults);
            $this->assertEquals(2, count($collectionModel->results));

            $model = $collectionModel->results[0];
            $this->assertEquals(Account::class, get_class($model));
            $this->assertEquals('297c2dc5-cc47-4afd-8ec8-74990b8761e9', $model->accountID);
            // TODO test for both BANK and other type
            $this->assertEquals('BANK', $model->type);
        });
    }

    public function testGetId()
    {
        $this->verifyGetId(Account::class, '297c2dc5-cc47-4afd-8ec8-74990b8761e9', function ($model) {
            $this->assertEquals('297c2dc5-cc47-4afd-8ec8-74990b8761e9', $model->accountID);
            $this->assertEquals('BNZ Cheque Account', $model->name);
        });
    }

    public function testGetByIds()
    {
        $this->verifyGetByIds(
            Account::class,
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
            Account::class,
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
            Account::class,
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
        $this->verifyDelete(Account::class, 11, function () {
            // TODO do actual checks
        });
    }

    public function testValidateRange()
    {
        $this->verifyValidateRange(Account::class);
    }

    public function testValidateRegexp()
    {
        $this->verifyValidateRegexp(Account::class);
    }

    public function testNotAllowedPropertyForTypeOnly()
    {
        $this->verifyNotAllowedPropertyForTypeOnly(Account::class);
    }

    public function testAbsentPropertyForType()
    {
        $this->verifyAbsentPropertyForType(Account::class);
    }

    public function testNotAllowedPropertyForTypeExcept()
    {
        $this->verifyAbsentPropertyForTypeExcept(Account::class);
    }

    public function testNotHaveMinimumPropertiesForCreate()
    {
        $this->verifyNotHaveMinimumPropertiesForCreate(Account::class);
    }
}
