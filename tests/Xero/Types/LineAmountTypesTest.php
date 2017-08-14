<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\LineAmountTypes;

class LineAmountTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testLineAmountTypesConstants()
    {
        $this->assertEquals(LineAmountTypes::EXCLUSIVE, 'EXCLUSIVE');
        $this->assertEquals(LineAmountTypes::INCLUSIVE, 'INCLUSIVE');
        $this->assertEquals(LineAmountTypes::NOTAX, 'NOTAX');
    }

    public function testLineAmountTypesDescriptions()
    {
        $lineAmountTypes = new LineAmountTypes;
        $expected = [
            LineAmountTypes::EXCLUSIVE => 'Line items are exclusive of tax',
            LineAmountTypes::INCLUSIVE => 'Line items are inclusive tax',
            LineAmountTypes::NOTAX => 'Line have no tax',
        ];

        $this->assertEquals($expected, $lineAmountTypes->descriptions);
    }
}
