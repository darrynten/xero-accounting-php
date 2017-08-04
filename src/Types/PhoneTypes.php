<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Phone Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PhoneTypes
{
    const DEFAULTPHONE = 'DEFAULT';
    const DDI = 'DDI';
    const MOBILE = 'MOBILE';
    const FAX = 'FAX';

    public $descriptions = [
        self::DEFAULTPHONE => 'Default phone number',
        self::DDI => 'Direct inward dialing phone number',
        self::MOBILE => 'Mobile phone number',
        self::FAX => 'Fax number',
    ];
}
