<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\BankTransactionsTypes;

class BankTransactionsTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testBankTransactionsTypesConstants()
    {
        $this->assertEquals(BankTransactionsTypes::RECEIVE, 'RECEIVE');
        $this->assertEquals(BankTransactionsTypes::RECEIVE_OVERPAYMENT, 'RECEIVE_OVERPAYMENT');
        $this->assertEquals(BankTransactionsTypes::RECEIVE_PREPAYMENT, 'RECEIVE_PREPAYMENT');
        $this->assertEquals(BankTransactionsTypes::SPEND, 'SPEND');
        $this->assertEquals(BankTransactionsTypes::SPEND_OVERPAYMENT, 'SPEND_OVERPAYMENT');
        $this->assertEquals(BankTransactionsTypes::SPEND_PREPAYMENT, 'SPEND_PREPAYMENT');
        $this->assertEquals(BankTransactionsTypes::RECEIVE_TRANSFER, 'RECEIVE_TRANSFER');
        $this->assertEquals(BankTransactionsTypes::SPEND_TRANSFER, 'SPEND_TRANSFER');
    }

    public function testBankTransactionsTypesDescriptions()
    {
        $bankTransactionsTypes = new BankTransactionsTypes;
        $expected = [
            BankTransactionsTypes::RECEIVE => 'Receive Bank Transaction',
            BankTransactionsTypes::RECEIVE_OVERPAYMENT => 'Receive overpayment',
            BankTransactionsTypes::RECEIVE_PREPAYMENT => 'Receive prepayment',
            BankTransactionsTypes::SPEND => 'Spend',
            BankTransactionsTypes::SPEND_OVERPAYMENT => 'Spend overpayment',
            BankTransactionsTypes::SPEND_PREPAYMENT => 'Spend prepayment',
            BankTransactionsTypes::RECEIVE_TRANSFER => 'Receive transfer',
            BankTransactionsTypes::SPEND_TRANSFER => 'Spend transfer',
        ];

        $this->assertEquals($expected, $bankTransactionsTypes->descriptions);
    }
}
