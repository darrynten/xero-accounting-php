<?php

namespace DarrynTen\Xero\Tests\Xero\Types;


use DarrynTen\Xero\BankAccountTypes;

class BankAccountTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testBankAccountTypes(){
        $this->assertEquals(BankAccountTypes::BANK, 'BANK');
        $this->assertEquals(BankAccountTypes::CREDITCARD, 'CREDITCARD');
        $this->assertEquals(BankAccountTypes::PAYPAL, 'PAYPAL');

        $account = new BankAccountTypes();
        $expected = [
            BankAccountTypes::BANK => 'Bank account',
            BankAccountTypes::CREDITCARD => 'Credit card account',
            BankAccountTypes::PAYPAL => 'Paypal account',
        ];

        $this->assertEquals($expected, $account->descriptions, "\$canonicalize = true", $delta = 0.0, $maxDepth = 2, $canonicalize = true);
    }
}