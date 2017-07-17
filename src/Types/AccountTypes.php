<?php

namespace DarrynTen\Xero;

/**
 * Xero Account Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class AccountTypes
{
    const BANK = 'BANK';
    const CURRENT = 'CURRENT';
    const CURRLIAB = 'CURRLIAB';
    const DEPRECIATN = 'DEPRECIATN';
    const DIRECTCOSTS = 'DIRECTCOSTS';
    const EQUITY = 'EQUITY';
    const EXPENSE = 'EXPENSE';
    const FIXED = 'FIXED';
    const INVENTORY = 'INVENTORY';
    const LIABILITY = 'LIABILITY';
    const NONCURRENT = 'NONCURRENT';
    const OTHERINCOME = 'OTHERINCOME';
    const OVERHEADS = 'OVERHEADS';
    const PREPAYMENT = 'PREPAYMENT';
    const REVENUE = 'REVENUE';
    const SALES = 'SALES';
    const TERMLIAB = 'TERMLIAB';
    const PAYGLIABILITY = 'PAYGLIABILITY';
    const SUPERANNUATIONEXPENSE = 'SUPERANNUATIONEXPENSE';
    const SUPERANNUATIONLIABILITY = 'SUPERANNUATIONLIABILITY';
    const WAGESEXPENSE = 'WAGESEXPENSE';
    const WAGESPAYABLELIABILITY = 'WAGESPAYABLELIABILITY';

    public $descriptions = [
        self::BANK => 'Bank account',
        self::CURRENT => 'Current Asset account',
        self::CURRLIAB => 'Current Liability account',
        self::DEPRECIATN => 'Depreciation account',
        self::DIRECTCOSTS => 'Direct Costs account',
        self::EQUITY => 'Equity account',
        self::EXPENSE => 'Expense account',
        self::FIXED => 'Fixed Asset account',
        self::INVENTORY => 'Inventory Asset account',
        self::LIABILITY => 'Liability account',
        self::NONCURRENT => 'Non-current Asset account',
        self::OTHERINCOME => 'Other Income account',
        self::OVERHEADS => 'Overhead account',
        self::PREPAYMENT => 'Prepayment account',
        self::REVENUE => 'Revenue account',
        self::SALES => 'Sale account',
        self::TERMLIAB => 'Non-current Liability account',
        self::PAYGLIABILITY => 'PAYG Liability account',
        self::SUPERANNUATIONEXPENSE => 'Superannuation Expense account',
        self::SUPERANNUATIONLIABILITY => 'Superannuation Liability account',
        self::WAGESEXPENSE => 'Wages Expense account',
        self::WAGESPAYABLELIABILITY => 'Wages Payable Liability account',
    ];
}
