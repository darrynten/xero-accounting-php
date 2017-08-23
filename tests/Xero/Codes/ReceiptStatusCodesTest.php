<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\ReceiptStatusCodes;

class ReceiptStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $receiptCodes = new ReceiptStatusCodes();
        $this->assertEquals('DRAFT', ReceiptStatusCodes::DRAFT);
        $this->assertEquals('SUBMITTED', ReceiptStatusCodes::SUBMITTED);
        $this->assertEquals('AUTHORISED', ReceiptStatusCodes::AUTHORISED);
        $this->assertEquals('DECLINED', ReceiptStatusCodes::DECLINED);

        $expected = [
            ReceiptStatusCodes::DRAFT => 'Draft receipt',
            ReceiptStatusCodes::SUBMITTED => 'Submitted receipt ',
            ReceiptStatusCodes::AUTHORISED => 'Authorised receipt',
            ReceiptStatusCodes::DECLINED => 'Declined receipt',
        ];

        $this->assertEquals($expected, $receiptCodes->descriptions);
    }
}
