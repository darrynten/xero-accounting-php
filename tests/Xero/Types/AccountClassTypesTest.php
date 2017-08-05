<?php

namespace DarrynTen\Xero\Tests\Types;

use DarrynTen\Xero\Types\AccountClassTypes;

class AccountClassTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountTypesConstants()
    {
        $this->assertEquals(AccountClassTypes::ASSET, 'ASSET');
        $this->assertEquals(AccountClassTypes::EQUITY, 'EQUITY');
        $this->assertEquals(AccountClassTypes::EXPENSE, 'EXPENSE');
        $this->assertEquals(AccountClassTypes::LIABILITY, 'LIABILITY');
        $this->assertEquals(AccountClassTypes::REVENUE, 'REVENUE');
    }

    public function testAccountTypesDescriptions()
    {
        $accountClass = new AccountClassTypes();
        $expected = [
            AccountClassTypes::ASSET => 'Account class asset',
            AccountClassTypes::EQUITY => 'Account class equity',
            AccountClassTypes::EXPENSE => 'Account class expense',
            AccountClassTypes::LIABILITY => 'Account class liability',
            AccountClassTypes::REVENUE => 'Account class revenue',
        ];

        $this->assertEquals($expected, $accountClass->descriptions);
    }
}
