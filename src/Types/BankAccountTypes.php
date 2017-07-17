<?php

namespace DarrynTen\Xero;

/**
 * Xero Bank Account Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class BankAccountTypes
{
    const BANK = 'BANK';
    const CREDITCARD = 'CREDITCARD';
    const PAYPAL = 'PAYPAL';

    public $descriptions = [
        self::BANK => 'Bank account',
        self::CREDITCARD => 'Credit card account',
        self::PAYPAL => 'Paypal account',
    ];
}
