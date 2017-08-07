<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero PrePayment Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian Maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PrePaymentStatusCodes
{
    const AUTHORISED = 'AUTHORISED';
    const PAID = 'PAID';
    const VOIDED = 'VOIDED';

    public $descriptions = [
        self::AUTHORISED => 'Authorised prepayment',
        self::PAID => 'Paid prepayment',
        self::VOIDED => 'Voided prepayment',
    ];
}
