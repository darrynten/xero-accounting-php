<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Overpayments Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class OverpaymentsTypes
{
    const RECEIVE_OVERPAYMENT = 'RECEIVE_OVERPAYMENT';
    const SPEND_OVERPAYMENT = 'SPEND_OVERPAYMENT';

    public $descriptions = [
        self::RECEIVE_OVERPAYMENT => 'Overpayment received',
        self::SPEND_OVERPAYMENT => 'Overpayment spend',
    ];
}
