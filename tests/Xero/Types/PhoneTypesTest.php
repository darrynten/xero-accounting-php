<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\PhoneTypes;

class PhoneTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testPhoneTypesConstants()
    {
        $this->assertEquals(PhoneTypes::DEFAULTPHONE, 'DEFAULT');
        $this->assertEquals(PhoneTypes::DDI, 'DDI');
        $this->assertEquals(PhoneTypes::MOBILE, 'MOBILE');
        $this->assertEquals(PhoneTypes::FAX, 'FAX');
    }

    public function testPhoneTypesDescriptions()
    {
        $PhoneTypes = new PhoneTypes;
        $expected = [
            PhoneTypes::DEFAULTPHONE => 'Default phone number',
            PhoneTypes::DDI => 'Direct inward dialing phone number',
            PhoneTypes::MOBILE => 'Mobile phone number',
            PhoneTypes::FAX => 'Fax number',
        ];

        $this->assertEquals($expected, $PhoneTypes->descriptions);
    }
}
