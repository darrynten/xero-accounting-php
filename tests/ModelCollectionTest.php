<?php

namespace DarrynTen\Xero\Tests\Xero\Models;

use DarrynTen\Xero\Models\Accounting\AccountModel;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelCollectionException;
use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\ModelCollection;

class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    //Simple tests before the example model and mocks are up and running
    public function testGetUndefinedException()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection ID Attempting to access undefined property');
        $this->expectExceptionCode(ModelCollectionException::GETTING_UNDEFINED_PROPERTY);

        throw new ModelCollectionException(ModelCollectionException::GETTING_UNDEFINED_PROPERTY, 'ID');
    }

    public function testRequiredPropertyException()
    {
        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('ModelCollection Total Missing required property in object');
        $this->expectExceptionCode(ModelCollectionException::MISSING_REQUIRED_PROPERTY);

        throw new ModelCollectionException(ModelCollectionException::MISSING_REQUIRED_PROPERTY, 'Total');
    }
}
