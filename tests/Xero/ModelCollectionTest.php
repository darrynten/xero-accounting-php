<?php

namespace DarrynTen\Xero\Tests\Xero;

use DarrynTen\Xero\Exception\ModelCollectionException;
use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Models\Accounting\Account;

class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $config = [
        'key' => 'key',
        'endpoint' => '//localhost:8082',
    ];

    public function testException()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection undefinedProperty Attempting to access undefined property');
        $this->expectExceptionCode(20201);

        $results = new \stdClass;
        $results->TotalResults = 0;
        $results->ReturnedResults = 0;
        $results->Results = [];
        $collection = new ModelCollection(Account::class, $this->config, $results);
        $undefinedProperty = $collection->undefinedProperty;
    }

    public function testRequiredTotalResults()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection TotalResults Missing required property in object');
        $this->expectExceptionCode(20202);

        $results = new \stdClass;
        $collection = new ModelCollection(Account::class, $this->config, $results);
    }

    public function testRequireReturnedResults()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection ReturnedResults Missing required property in object');
        $this->expectExceptionCode(20202);

        $results = new \stdClass;
        $results->TotalResults = 1;
        $collection = new ModelCollection(Account::class, $this->config, $results);
    }

    public function testRequireResults()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection Results Missing required property in object');
        $this->expectExceptionCode(20202);

        $results = new \stdClass;
        $results->TotalResults = 1;
        $results->ReturnedResults = 1;
        $collection = new ModelCollection(ModelCollection::class, $this->config, $results);
    }

    /**
    public function testResultsAsArray()
    {
        // TODO can't we use the get all result here?
        $pathToMock = __DIR__ . "/../mocks/Accounting/Account/GET_Accounts_xx.xml";
        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $results[] = $data->Account;
        $collection = new ModelCollection(Account::class, $this->config, $results);
        $this->assertEquals(1, $collection->totalResults);
        $this->assertEquals(1, $collection->returnedResults);
    }
    **/
}
