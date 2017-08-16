<?php

namespace DarrynTen\Xero\Tests\Types;

use DarrynTen\Xero\Types\UserRolesTypes;

class UserRolesTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountTypesConstants()
    {
        $this->assertEquals(UserRolesTypes::READONLY, 'READONLY');
        $this->assertEquals(UserRolesTypes::INVOICEONLY, 'INVOICEONLY');
        $this->assertEquals(UserRolesTypes::STANDARD, 'STANDARD');
        $this->assertEquals(UserRolesTypes::FINANCIALADVISER, 'FINANCIALADVISER');
        $this->assertEquals(UserRolesTypes::MANAGEDCLIENT, 'MANAGEDCLIENT');
        $this->assertEquals(UserRolesTypes::CASHBOOKCLIENT, 'CASHBOOKCLIENT');
    }

    public function testAccountTypesDescriptions()
    {
        $userRoles = new UserRolesTypes();
        $expected = [
            UserRolesTypes::READONLY => 'Read only user',
            UserRolesTypes::INVOICEONLY => 'Invoice only user',
            UserRolesTypes::STANDARD => 'Standard user',
            UserRolesTypes::FINANCIALADVISER => 'Financial adviser role',
            UserRolesTypes::MANAGEDCLIENT => 'Managed client role',
            UserRolesTypes::CASHBOOKCLIENT => 'Cashbook client role',
        ];

        $this->assertEquals($expected, $userRoles->descriptions);
    }
}
