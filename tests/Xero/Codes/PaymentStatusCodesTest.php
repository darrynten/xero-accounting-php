<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\PaymentStatusCodes;

class PaymentStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $paymentStatusCodes = new PaymentStatusCodes();
        $this->assertEquals('AUTHORISED', PaymentStatusCodes::AUTHORISED);
        $this->assertEquals('DELETED', PaymentStatusCodes::DELETED);

        $expected = [
            PaymentStatusCodes::AUTHORISED => 'Authorised payment',
            PaymentStatusCodes::DELETED => 'Deleted payment',
        ];

        $this->assertEquals($expected, $paymentStatusCodes->descriptions);
    }
}
