<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Bank Transaction Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class BankTransactionsTypes
{
    const RECEIVE = 'RECEIVE';
    const RECEIVE_OVERPAYMENT = 'RECEIVE_OVERPAYMENT';
    const RECEIVE_PREPAYMENT = 'RECEIVE_PREPAYMENT';
    const SPEND = 'SPEND';
    const SPEND_OVERPAYMENT = 'SPEND_OVERPAYMENT';
    const SPEND_PREPAYMENT = 'SPEND_PREPAYMENT';
    /*
     * The following values are only supported via the GET method at the moment
     */
    const RECEIVE_TRANSFER = 'RECEIVE_TRANSFER';
    const SPEND_TRANSFER = 'SPEND_TRANSFER';

    public $descriptions = [
        self::RECEIVE => 'Receive Bank Transaction',
        self::RECEIVE_OVERPAYMENT => 'Receive overpayment',
        self::RECEIVE_PREPAYMENT => 'Receive prepayment',
        self::SPEND => 'Spend',
        self::SPEND_OVERPAYMENT => 'Spend overpayment',
        self::SPEND_PREPAYMENT => 'Spend prepayment',
        self::RECEIVE_TRANSFER => 'Receive transfer',
        self::SPEND_TRANSFER => 'Spend transfer',
    ];
}
