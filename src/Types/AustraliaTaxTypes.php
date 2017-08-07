<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Australia Tax Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class AustraliaTaxTypes
{
    const OUTPUT = 'OUTPUT';
    const INPUT = 'INPUT';
    const CAPEXINPUT = 'CAPEXINPUT';
    const EXEMPTEXPORT = 'EXEMPTEXPORT';
    const EXEMPTEXPENSES = 'EXEMPTEXPENSES';
    const EXEMPTCAPITAL = 'EXEMPTCAPITAL';
    const EXEMPTOUTPUT = 'EXEMPTOUTPUT';
    const INPUTTAXED = 'INPUTTAXED';
    const BASEXCLUDED = 'BASEXCLUDED';
    const GSTONCAPIMPORTS = 'GSTONCAPIMPORTS';
    const GSTONIMPORTS = 'GSTONIMPORTS';

    public $descriptions = [
        self::OUTPUT => 'GST on Income',
        self::INPUT => 'GST on Expenses',
        self::CAPEXINPUT => 'GST on Capital',
        self::EXEMPTEXPORT => 'GST Free Exports',
        self::EXEMPTEXPENSES => 'GST Free Expenses',
        self::EXEMPTCAPITAL => 'GST Free Capital',
        self::EXEMPTOUTPUT => 'GST Free Income',
        self::INPUTTAXED => 'Input Taxed',
        self::BASEXCLUDED => 'BAS Excluded',
        self::GSTONCAPIMPORTS => 'GST on Capital Imports',
        self::GSTONIMPORTS => 'GST on Imports',
    ];
}