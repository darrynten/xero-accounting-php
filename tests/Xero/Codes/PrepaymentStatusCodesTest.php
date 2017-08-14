<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\PrePaymentStatusCodes;

class PrePaymentStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $prePaymentStatusCodes = new PrePaymentStatusCodes();
        $this->assertEquals('AUTHORISED', PrePaymentStatusCodes::AUTHORISED);
        $this->assertEquals('PAID', PrePaymentStatusCodes::PAID);
        $this->assertEquals('VOIDED', PrePaymentStatusCodes::VOIDED);

        $expected = [
            PrePaymentStatusCodes::AUTHORISED => 'Authorised prepayment',
            PrePaymentStatusCodes::PAID => 'Paid prepayment',
            PrePaymentStatusCodes::VOIDED => 'Voided prepayment',
        ];

        $this->assertEquals($expected, $prePaymentStatusCodes->descriptions);
    }
}
