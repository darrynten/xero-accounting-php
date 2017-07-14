<?php

namespace DarrynTen\Xero\Tests\Xero\Models;

use DarrynTen\Xero\Tests\Xero\Models\BaseModelTest;
use DarrynTen\Xero\Models\Accounting\Accounts;
// use DarrynTen\Xero\Models\TaxType;
// use DarrynTen\Xero\Models\AccountCategory;
use DarrynTen\Xero\Request\RequestHandler;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;

class AccountsModelTest extends BaseModelTest
{
    public function testInstanceOf()
    {
        $this->verifyInstanceOf(Accounts::class);
    }

    public function testSetUndefined()
    {
        $this->verifySetUndefined(Accounts::class);
    }

    public function testGetUndefined()
    {
        $this->verifyGetUndefined(Accounts::class);
    }

    public function testCanNotNullify()
    {
        $this->verifyCanNotNullify(Accounts::class, 'type');
    }

    public function testCanNullify()
    {
        $this->verifyCanNullify(Accounts::class, 'bankAccountNumber');
    }

    public function testBadImport()
    {
        $this->verifyBadImport(Accounts::class, 'name');
    }

    public function testInject()
    {
        $this->verifyInject(Accounts::class, function ($model, $data) {
            $this->assertEquals($model->accountId, '');
            $this->assertEquals($model->name, 'sample string 2');

            // TODO

            $objArray = json_decode($model->toJson(), true);
            $this->assertCount(15, $objArray);
        });
    }

    public function testAttributes()
    {
        $this->verifyAttributes(Accounts::class, [
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
        $this->verifyFeatures(Accounts::class, [
            'all' => true,
            'get' => true,
            'delete' => true,
            'create' => true,
            'update' => true,
        ]);
    }

    public function testGetAll()
    {
        $this->verifyGetAll(Accounts::class, function ($results, $data) {
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
        $this->verifyGetId(Accounts::class, 2, function ($model) {
            $this->assertEquals($model->id, 11);
            $this->assertTrue($model->active);
            $this->assertEquals($model->name, 'sample string 2');
        });
    }

    public function testSave()
    {
        $this->verifySave(Accounts::class, function ($response) {
            $this->assertEquals(11, $response->ID);
            // TODO Do actual checks
        });
    }

    public function testDelete()
    {
        $this->verifyDelete(Accounts::class, 11, function () {
            // TODO do actual checks
        });
    }

    public function testAuth()
    {
        $this->verifyRequestWithAuth(Accounts::class, 'Save');
    }
}
