<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero LineAmount Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class LineAmountTypes
{
    const EXCLUSIVE = 'EXCLUSIVE';
    const INCLUSIVE = 'INCLUSIVE';
    const NOTAX = 'NOTAX';

    public $descriptions = [
        self::EXCLUSIVE => 'Line items are exclusive of tax',
        self::INCLUSIVE => 'Line items are inclusive tax',
        self::NOTAX => 'Line have no tax',
    ];
}
