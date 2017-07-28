<?php

namespace DarrynTen\Xero\Tests\Xero\Models;

use DarrynTen\Xero\Models\Accounting\AccountModel;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelCollectionException;
use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\ModelCollection;

class ModelCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUndefinedException()
    {


        $this->expectException(ModelCollectionException::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(ModelCollectionException::GETTING_UNDEFINED_PROPERTY);


    }
}
