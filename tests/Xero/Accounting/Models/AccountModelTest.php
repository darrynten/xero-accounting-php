<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

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
                    'except' => [
                        'type' => 'BANK',
                    ],
                ],
                'bankAccountType' => [
                    'type' => 'string',
                    'nullable' => true,
                    'readonly' => false,
                    'valid' => 'bankAccountTypes',
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
}
