<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Account Class Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class AccountClassTypes
{
    const ASSET = 'ASSET';
    const EQUITY = 'EQUITY';
    const EXPENSE = 'EXPENSE';
    const LIABILITY = 'LIABILITY';
    const REVENUE = 'REVENUE';

    public $descriptions = [
        self::ASSET => 'Account class asset',
        self::EQUITY => 'Account class equity',
        self::EXPENSE => 'Account class expense',
        self::LIABILITY => 'Account class liability',
        self::REVENUE => 'Account class revenue',
    ];
}
