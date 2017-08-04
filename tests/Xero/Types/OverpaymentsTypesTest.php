<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\OverpaymentsTypes;

class OverpaymentsTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testOverpaymentsTypesConstants()
    {
        $this->assertEquals(OverpaymentsTypes::RECEIVE_OVERPAYMENT, 'RECEIVE_OVERPAYMENT');
        $this->assertEquals(OverpaymentsTypes::SPEND_OVERPAYMENT, 'SPEND_OVERPAYMENT');
    }

    public function testOverpaymentsTypesDescriptions()
    {
        $overpaymentsTypes = new OverpaymentsTypes;
        $expected = [
            OverpaymentsTypes::RECEIVE_OVERPAYMENT => 'Overpayment received',
            OverpaymentsTypes::SPEND_OVERPAYMENT => 'Overpayment spend',
        ];

        $this->assertEquals($expected, $overpaymentsTypes->descriptions);
    }
}