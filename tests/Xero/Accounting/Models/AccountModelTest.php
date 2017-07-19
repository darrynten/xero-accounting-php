<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting\Models;

use DarrynTen\Xero\Tests\Xero\Accounting\BaseAccountingModelTest;
use DarrynTen\Xero\Accounting\Models\AccountModel;
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
        $this->verifyInject(AccountModel::class, function ($model, $data) {
            $this->assertEquals($model->accountId, '');
            $this->assertEquals($model->name, 'sample string 2');

            // TODO

            $objArray = json_decode($model->toJson(), true);
            $this->assertCount(15, $objArray);
        });
    }

    public function testAttributes()
    {
        $this->verifyAttributes(AccountModel::class, [
            'accountID' => [
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
                'except' => [
                    'type' => 'BANK',
                ],
            ],
            'bankAccountType' => [
                'type' => 'BankAccountType',
                'nullable' => true,
                'readonly' => false,
                'types' => 'bankAccountTypes',
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
            'where' => [],
            'order' => [],
        ]);
    }

    public function testFeatures()
    {
        $this->verifyFeatures(AccountModel::class, [
            'all' => true,
            'get' => true,
            'delete' => true,
            'create' => true,
            'update' => true,
        ]);
    }

    public function testGetAll()
    {
        $this->verifyGetAll(AccountModel::class, function ($results, $data) {
            $this->assertEquals(2, count($results));
            $model = new Account($this->config);
            $data = json_decode(json_encode($results[0], JSON_PRESERVE_ZERO_FRACTION));
            $model->loadResult($data);

            $this->assertEquals($model->id, 11);
            $this->assertTrue($model->active);
            $this->assertEquals($model->name, 'sample string 2');
        });
    }

    public function testGetId()
    {
        $this->verifyGetId(AccountModel::class, 2, function ($model) {
            $this->assertEquals($model->id, 11);
            $this->assertTrue($model->active);
            $this->assertEquals($model->name, 'sample string 2');
        });
    }

    public function testSave()
    {
        $this->verifySave(AccountModel::class, function ($response) {
            $this->assertEquals(11, $response->ID);
            // TODO Do actual checks
        });
    }

    public function testDelete()
    {
        $this->verifyDelete(AccountModel::class, 11, function () {
            // TODO do actual checks
        });
    }

    public function testAuth()
    {
        $this->verifyRequestWithAuth(AccountModel::class, 'Save');
    }
}
