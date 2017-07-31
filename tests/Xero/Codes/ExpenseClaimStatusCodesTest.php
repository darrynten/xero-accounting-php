<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\ExpenseClaimStatusCodes;

class ExpenseClaimStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $expenseClaimCodes = new ExpenseClaimStatusCodes();
        $this->assertEquals('SUBMITTED', ExpenseClaimStatusCodes::SUBMITTED);
        $this->assertEquals('AUTHORISED', ExpenseClaimStatusCodes::AUTHORISED);
        $this->assertEquals('PAID', ExpenseClaimStatusCodes::PAID);

        $expected = [
            ExpenseClaimStatusCodes::SUBMITTED => 'Submitted expense claim',
            ExpenseClaimStatusCodes::AUTHORISED => 'Authorised expense claim',
            ExpenseClaimStatusCodes::PAID => 'Paid expense claim',
        ];

        $this->assertEquals($expected, $expenseClaimCodes->descriptions);
    }
}
