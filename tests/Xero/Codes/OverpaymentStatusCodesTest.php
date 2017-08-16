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
            OverpaymentStatusCodes::AUTHORISED => 'Authorised overpayment',
            OverpaymentStatusCodes::PAID => 'Paid overpayment',
            OverpaymentStatusCodes::VOIDED => 'Voided overpayment',
        ];

        $this->assertEquals($expected, $overpaymentCodes->descriptions);
    }
}
