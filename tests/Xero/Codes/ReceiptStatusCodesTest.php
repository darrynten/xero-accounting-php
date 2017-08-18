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
            ReceiptStatusCodes::DRAFT => 'A draft receipt (default)',
            ReceiptStatusCodes::SUBMITTED => 'Receipt has been submitted as part of an expense claim',
            ReceiptStatusCodes::AUTHORISED => 'Receipt has been authorised in the Xero app',
            ReceiptStatusCodes::DECLINED => 'Receipt has been declined in the Xero app',
        ];

        $this->assertEquals($expected, $receiptCodes->descriptions);
    }
}
