<?php

namespace DarrynTen\Xero\Tests\Codes;

use DarrynTen\Xero\Codes\ContactStatusCodes;

class ContactStatusCodesTest extends \PHPUnit_Framework_TestCase
{
    public function testCodes()
    {
        $contactStatusCodes = new ContactStatusCodes();
        $this->assertEquals('ACTIVE', ContactStatusCodes::ACTIVE);
        $this->assertEquals('ARCHIVED', ContactStatusCodes::ARCHIVED);

        $expected = [
            ContactStatusCodes::ACTIVE => 'Active Contact',
            ContactStatusCodes::ARCHIVED => 'Archived Contact',
        ];

        $this->assertEquals($expected, $contactStatusCodes->descriptions);
    }
}
