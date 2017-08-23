<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\InvoiceStatusCodes;

class InvoiceStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $invoiceCodes = new InvoiceStatusCodes();
        $this->assertEquals('DRAFT', InvoiceStatusCodes::DRAFT);
        $this->assertEquals('SUBMITTED', InvoiceStatusCodes::SUBMITTED);
        $this->assertEquals('DELETED', InvoiceStatusCodes::DELETED);
        $this->assertEquals('AUTHORISED', InvoiceStatusCodes::AUTHORISED);
        $this->assertEquals('PAID', InvoiceStatusCodes::PAID);
        $this->assertEquals('VOIDED', InvoiceStatusCodes::VOIDED);

        $expected = [
            InvoiceStatusCodes::DRAFT => 'Draft invoice',
            InvoiceStatusCodes::SUBMITTED => 'Invoice awaiting approval ',
            InvoiceStatusCodes::DELETED => 'Deleted invoice',
            InvoiceStatusCodes::AUTHORISED => 'Approved invoice',
            InvoiceStatusCodes::PAID => 'Paid invoice  ',
            InvoiceStatusCodes::VOIDED => 'Voided invoice',
        ];

        $this->assertEquals($expected, $invoiceCodes->descriptions);
    }
}
