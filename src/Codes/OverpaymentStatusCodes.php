<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Overpayment Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class OverpaymentStatusCodes
{
    const AUTHORISED = 'AUTHORISED';
    const PAID = 'PAID';
    const VOIDED = 'VOIDED';

    public $descriptions = [
        self::AUTHORISED => 'Authorised overpayment',
        self::PAID => 'Paid overpayment',
        self::VOIDED => 'Voided overpayment',
    ];
}
