<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\PrepaymentsTypes;

class PrepaymentsTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testPrepaymentsTypesConstants()
    {
        $this->assertEquals(PrepaymentsTypes::RECEIVE_PREPAYMENT, 'RECEIVE_PREPAYMENT');
        $this->assertEquals(PrepaymentsTypes::SPEND_PREPAYMENT, 'SPEND_PREPAYMENT');
    }

    public function testPrepaymentsTypesDescriptions()
    {
        $prepaymentsTypes = new PrepaymentsTypes;
        $expected = [
            PrepaymentsTypes::RECEIVE_PREPAYMENT => 'Receive prepayment',
            PrepaymentsTypes::SPEND_PREPAYMENT => 'Spend prepayment',
        ];

        $this->assertEquals($expected, $prepaymentsTypes->descriptions);
    }
}
