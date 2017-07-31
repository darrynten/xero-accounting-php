<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\AccountStatusCodes;

class AccountStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes(){
        $accountCodes = new AccountStatusCodes();
        $this->assertEquals('ACTIVE', AccountStatusCodes::ACTIVE);
        $this->assertEquals('ARCHIVED', AccountStatusCodes::ARCHIVED);

        $expected = [
            self::ACTIVE => 'Active account',
            self::ARCHIVED => 'Archived account',
        ];

        $this->assertEquals($expected, $accountCodes->descriptions, "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }
}