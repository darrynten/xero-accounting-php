<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Prepayments Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PrepaymentsTypes
{
    const RECEIVE_PREPAYMENT = 'RECEIVE_PREPAYMENT';
    const SPEND_PREPAYMENT = 'SPEND_PREPAYMENT';

    public $descriptions = [
        self::RECEIVE_PREPAYMENT => 'Receive prepayment',
        self::SPEND_PREPAYMENT => 'Spend prepayment',
    ];
}
