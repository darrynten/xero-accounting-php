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
            InvoiceStatusCodes::DRAFT => 'A Draft Invoice (default)',
            InvoiceStatusCodes::SUBMITTED => 'An Awaiting Approval Invoice',
            InvoiceStatusCodes::DELETED => 'A Deleted Invoice',
            InvoiceStatusCodes::AUTHORISED => 'An Invoice that is Approved and Awaiting Payment OR partially paid',
            InvoiceStatusCodes::PAID => 'An Invoice that is completely Paid',
            InvoiceStatusCodes::VOIDED => 'A Voided Invoice',
        ];

        $this->assertEquals($expected, $invoiceCodes->descriptions);
    }
}
