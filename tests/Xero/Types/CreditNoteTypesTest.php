<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\CreditNoteTypes;

class CreditNoteTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testCreditNoteTypesConstants()
    {
        $this->assertEquals(CreditNoteTypes::ACCPAYCREDIT, 'ACCPAYCREDIT');
        $this->assertEquals(CreditNoteTypes::ACCRECCREDIT, 'ACCRECCREDIT');
    }

    public function testCreditNoteTypesDescriptions()
    {
        $creditNoteTypes = new CreditNoteTypes;
        $expected = [
            CreditNoteTypes::ACCPAYCREDIT => 'An Accounts Payable(supplier) Credit Note',
            CreditNoteTypes::ACCRECCREDIT => 'An Account Receivable(customer) Credit Note',
        ];

        $this->assertEquals($expected, $creditNoteTypes->descriptions);
    }
}
