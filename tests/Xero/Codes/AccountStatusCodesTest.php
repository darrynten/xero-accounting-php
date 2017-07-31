<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\AccountStatusCodes;

class AccountStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $accountCodes = new AccountStatusCodes();
        $this->assertEquals('ACTIVE', AccountStatusCodes::ACTIVE);
        $this->assertEquals('ARCHIVED', AccountStatusCodes::ARCHIVED);

        $expected = [
            AccountStatusCodes::ACTIVE => 'Active account',
            AccountStatusCodes::ARCHIVED => 'Archived account',
        ];

        $this->assertEquals($expected, $accountCodes->descriptions);
    }
}
