<?php

namespace DarrynTen\Xero\Tests\Types;

use DarrynTen\Xero\Types\AccountTypes;

class AccountTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountTypesConstants()
    {
        $this->assertEquals(AccountTypes::BANK, 'BANK');
        $this->assertEquals(AccountTypes::CURRENT, 'CURRENT');
        $this->assertEquals(AccountTypes::CURRLIAB, 'CURRLIAB');
        $this->assertEquals(AccountTypes::DEPRECIATN, 'DEPRECIATN');
        $this->assertEquals(AccountTypes::DIRECTCOSTS, 'DIRECTCOSTS');
        $this->assertEquals(AccountTypes::EQUITY, 'EQUITY');
        $this->assertEquals(AccountTypes::EXPENSE, 'EXPENSE');
        $this->assertEquals(AccountTypes::FIXED, 'FIXED');
        $this->assertEquals(AccountTypes::INVENTORY, 'INVENTORY');
        $this->assertEquals(AccountTypes::LIABILITY, 'LIABILITY');
        $this->assertEquals(AccountTypes::NONCURRENT, 'NONCURRENT');
        $this->assertEquals(AccountTypes::OTHERINCOME, 'OTHERINCOME');
        $this->assertEquals(AccountTypes::OVERHEADS, 'OVERHEADS');
        $this->assertEquals(AccountTypes::PREPAYMENT, 'PREPAYMENT');
        $this->assertEquals(AccountTypes::REVENUE, 'REVENUE');
        $this->assertEquals(AccountTypes::SALES, 'SALES');
        $this->assertEquals(AccountTypes::TERMLIAB, 'TERMLIAB');
        $this->assertEquals(AccountTypes::PAYGLIABILITY, 'PAYGLIABILITY');
        $this->assertEquals(AccountTypes::SUPERANNUATIONEXPENSE, 'SUPERANNUATIONEXPENSE');
        $this->assertEquals(AccountTypes::SUPERANNUATIONLIABILITY, 'SUPERANNUATIONLIABILITY');
        $this->assertEquals(AccountTypes::WAGESEXPENSE, 'WAGESEXPENSE');
        $this->assertEquals(AccountTypes::WAGESPAYABLELIABILITY, 'WAGESPAYABLELIABILITY');
    }

    public function testAccountTypesDescriptions()
    {
        $account = new AccountTypes();
        $expected = [
            AccountTypes::BANK => 'Bank account',
            AccountTypes::CURRENT => 'Current Asset account',
            AccountTypes::CURRLIAB => 'Current Liability account',
            AccountTypes::DEPRECIATN => 'Depreciation account',
            AccountTypes::DIRECTCOSTS => 'Direct Costs account',
            AccountTypes::EQUITY => 'Equity account',
            AccountTypes::EXPENSE => 'Expense account',
            AccountTypes::FIXED => 'Fixed Asset account',
            AccountTypes::INVENTORY => 'Inventory Asset account',
            AccountTypes::LIABILITY => 'Liability account',
            AccountTypes::NONCURRENT => 'Non-current Asset account',
            AccountTypes::OTHERINCOME => 'Other Income account',
            AccountTypes::OVERHEADS => 'Overhead account',
            AccountTypes::PREPAYMENT => 'Prepayment account',
            AccountTypes::REVENUE => 'Revenue account',
            AccountTypes::SALES => 'Sale account',
            AccountTypes::TERMLIAB => 'Non-current Liability account',
            AccountTypes::PAYGLIABILITY => 'PAYG Liability account',
            AccountTypes::SUPERANNUATIONEXPENSE => 'Superannuation Expense account',
            AccountTypes::SUPERANNUATIONLIABILITY => 'Superannuation Liability account',
            AccountTypes::WAGESEXPENSE => 'Wages Expense account',
            AccountTypes::WAGESPAYABLELIABILITY => 'Wages Payable Liability account',
        ];

        $this->assertEquals($expected, $account->descriptions);
    }
}
