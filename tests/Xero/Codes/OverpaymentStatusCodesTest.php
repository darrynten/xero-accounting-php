<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\OverpaymentStatusCodes;

class OverpaymentStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $overpaymentCodes = new OverpaymentStatusCodes();
        $this->assertEquals('AUTHORISED', OverpaymentStatusCodes::AUTHORISED);
        $this->assertEquals('PAID', OverpaymentStatusCodes::PAID);
        $this->assertEquals('VOIDED', OverpaymentStatusCodes::VOIDED);

        $expected = [
            OverpaymentStatusCodes::AUTHORISED => 'An overpayment has been authorised',
            OverpaymentStatusCodes::PAID => 'An overpayment has been paid',
            OverpaymentStatusCodes::VOIDED => 'An overpayment has been voided',
        ];

        $this->assertEquals($expected, $overpaymentCodes->descriptions);
    }
}
