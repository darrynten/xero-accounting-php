<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\InvoiceTypes;

class InvoiceTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoiceTypesConstants()
    {
        $this->assertEquals(InvoiceTypes::ACCPAY, 'ACCPAY');
        $this->assertEquals(InvoiceTypes::ACCREC, 'ACCREC');
    }

    public function testInvoiceTypesDescriptions()
    {
        $invoiceTypes = new InvoiceTypes;
        $expected = [
            InvoiceTypes::ACCPAY => 'A bill - commonly known as a Accounts Payable or supplier invoice',
            InvoiceTypes::ACCREC => 'A sales invoice - commonly known as an Accounts Receivable or customer invoice',
        ];

        $this->assertEquals($expected, $invoiceTypes->descriptions);
    }
}