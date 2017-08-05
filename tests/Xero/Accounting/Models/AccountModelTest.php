<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Models\Accounting\AccountModel;
use DarrynTen\Xero\Tests\Xero\Accounting\BaseAccountingModelTest;
use DarrynTen\Xero\Request\RequestHandler;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;

class AccountsModelTest extends BaseAccountingModelTest
{
    public function testInstanceOf()
    {
        $this->verifyInstanceOf(AccountModel::class);
    }

    public function testSetUndefined()
    {
        $this->verifySetUndefined(AccountModel::class);
    }

    public function testGetUndefined()
    {
        $this->verifyGetUndefined(AccountModel::class);
    }

    public function testCanNotNullify()
    {
        $this->verifyCanNotNullify(AccountModel::class, 'type');
    }

    public function testCanNullify()
    {
        $this->verifyCanNullify(AccountModel::class, 'bankAccountNumber');
    }

    public function testBadImport()
    {
        $this->verifyBadImport(AccountModel::class, 'name');
    }

    public function testNotSupportedAll()
    {
        $this->verifyNotSupportedAll(AccountModel::class);
    }

    public function testNotSupportedGet()
    {
        $this->verifyNotSupportedGet(AccountModel::class);
    }

    public function testNotSupportedGetByIds()
    {
        $this->verifyNotSupportedGetByIds(AccountModel::class);
    }

    public function testNotSupportedDelete()
    {
        $this->verifyNotSupportedDelete(AccountModel::class);
    }

    public function testNotSupportedCreate()
    {
        $this->verifyNotSupportedCreate(AccountModel::class);
    }

    public function testNotSupportedUpdate()
    {
        $this->verifyNotSupportedUpdate(AccountModel::class);
    }

    public function testNullWithoutNullableAtribute()
    {
        $this->verifyCantBeNull(AccountModel::class);
    }

    public function testWriteWithReadOnly()
    {
        $this->verifyCantBeWritten(AccountModel::class);
    }

    public function testNotSupportedFilter()
    {
        $this->verifyNotSupportedFilter(AccountModel::class);
    }

    public function testNotSupportedOrder()
    {
        $this->verifyNotSupportedOrder(AccountModel::class);
    }

    public function testFilterByUnknownValue()
    {
        $this->verifyFilterByUnknownValue(AccountModel::class);
    }

    public function testOrderWithWrongParameters()
    {
        $this->verifyOrderWithWrongParameters(AccountModel::class);
    }

    public function testOrderWithUnknownField()
    {
        $this->verifyOrderWithUnknownField(AccountModel::class);
    }

    public function testIdMissingOnCreate()
    {
        $this->verifyIdMissingOnCreate(AccountModel::class);
    }

    public function testMissingRequiredProperty()
    {
        $this->verifyMissingRequiredProperty(AccountModel::class);
    }

    public function testInject()
    {
        $this->verifyInject(AccountModel::class, function ($model) {
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
            AccountModel::class,
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

        $model = $this->injectPropertyInModel(AccountModel::class, 'fields', $fields);

        $this->assertEquals('some', $model->accountID);
    }

    public function testFeatures()
    {
        $this->verifyFeatures(AccountModel::class, [
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
        $this->verifyGetAll(AccountModel::class, function ($collectionModel) {
            $this->assertEquals(ModelCollection::class, get_class($collectionModel));
            $this->assertEquals(2, $collectionModel->totalResults);
            $this->assertEquals(2, $collectionModel->returnedResults);
            $this->assertEquals(2, count($collectionModel->results));

            $model = $collectionModel->results[0];
            $this->assertEquals(AccountModel::class, get_class($model));
            $this->assertEquals('297c2dc5-cc47-4afd-8ec8-74990b8761e9', $model->accountID);
            $this->assertEquals('BANK', $model->type);
        });
    }

    public function testGetId()
    {
        $this->verifyGetId(AccountModel::class, '297c2dc5-cc47-4afd-8ec8-74990b8761e9', function ($model) {
            $this->assertEquals('297c2dc5-cc47-4afd-8ec8-74990b8761e9', $model->accountID);
            $this->assertEquals('BNZ Cheque Account', $model->name);
        });
    }

    public function testGetByIds()
    {
        $this->verifyGetByIds(
            AccountModel::class,
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
            AccountModel::class,
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
            AccountModel::class,
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
        $this->verifyDelete(AccountModel::class, 11, function () {
            // TODO do actual checks
        });
    }

    public function testValidateRange()
    {
        $this->verifyValidateRange(AccountModel::class);
    }

    public function testValidateRegexp()
    {
        $this->verifyValidateRegexp(AccountModel::class);
    }

    public function testNotAllowedPropertyForTypeOnly()
    {
        $this->verifyNotAllowedPropertyForTypeOnly(AccountModel::class);
    }

    public function testAbsentPropertyForType()
    {
        $this->verifyAbsentPropertyForType(AccountModel::class);
    }

    public function testNotAllowedPropertyForTypeExcept()
    {
        $this->verifyAbsentPropertyForTypeExcept(AccountModel::class);
    }

    public function testNotHaveMinimumPropertiesForCreate()
    {
        $this->verifyNotHaveMinimumPropertiesForCreate(AccountModel::class);
    }
}
