<?php

namespace DarrynTen\Xero\Tests\Xero\Types;

use DarrynTen\Xero\Types\AustraliaTaxTypes;

class AustraliaTaxTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testAustraliaTaxTypes()
    {
        $this->assertEquals(AustraliaTaxTypes::OUTPUT, 'OUTPUT');
        $this->assertEquals(AustraliaTaxTypes::INPUT, 'INPUT');
        $this->assertEquals(AustraliaTaxTypes::CAPEXINPUT, 'CAPEXINPUT');
        $this->assertEquals(AustraliaTaxTypes::EXEMPTEXPORT, 'EXEMPTEXPORT');
        $this->assertEquals(AustraliaTaxTypes::EXEMPTEXPENSES, 'EXEMPTEXPENSES');
        $this->assertEquals(AustraliaTaxTypes::EXEMPTCAPITAL, 'EXEMPTCAPITAL');
        $this->assertEquals(AustraliaTaxTypes::EXEMPTOUTPUT, 'EXEMPTOUTPUT');
        $this->assertEquals(AustraliaTaxTypes::INPUTTAXED, 'INPUTTAXED');
        $this->assertEquals(AustraliaTaxTypes::BASEXCLUDED, 'BASEXCLUDED');
        $this->assertEquals(AustraliaTaxTypes::GSTONCAPIMPORTS, 'GSTONCAPIMPORTS');
        $this->assertEquals(AustraliaTaxTypes::GSTONIMPORTS, 'GSTONIMPORTS');

        $australiaTax = new AustraliaTaxTypes();
        $expected = [
            AustraliaTaxTypes::OUTPUT => 'GST on Income',
            AustraliaTaxTypes::INPUT => 'GST on Expenses',
            AustraliaTaxTypes::CAPEXINPUT => 'GST on Capital',
            AustraliaTaxTypes::EXEMPTEXPORT => 'GST Free Exports',
            AustraliaTaxTypes::EXEMPTEXPENSES => 'GST Free Expenses',
            AustraliaTaxTypes::EXEMPTCAPITAL => 'GST Free Capital',
            AustraliaTaxTypes::EXEMPTOUTPUT => 'GST Free Income',
            AustraliaTaxTypes::INPUTTAXED => 'Input Taxed',
            AustraliaTaxTypes::BASEXCLUDED => 'BAS Excluded',
            AustraliaTaxTypes::GSTONCAPIMPORTS => 'GST on Capital Imports',
            AustraliaTaxTypes::GSTONIMPORTS => 'GST on Imports',
        ];

        $this->assertEquals($expected, $australiaTax->descriptions);
    }
}