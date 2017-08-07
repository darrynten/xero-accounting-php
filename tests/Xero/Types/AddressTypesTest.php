<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\AddressTypes;

class AddressTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testAddressTypes()
    {
        $this->assertEquals(AddressTypes::POBOX, 'POBOX');
        $this->assertEquals(AddressTypes::STREET, 'STREET');
        $this->assertEquals(AddressTypes::DELIVERY, 'DELIVERY');

        $address = new AddressTypes();
        $expected = [
            AddressTypes::POBOX => 'The default mailing address for invoices',
            AddressTypes::STREET =>  'The default street for invoices',
            AddressTypes::DELIVERY => 'Read-only. The delivery address of the Xero organisation',
        ];

        $this->assertEquals($expected, $address->descriptions);
    }
}
