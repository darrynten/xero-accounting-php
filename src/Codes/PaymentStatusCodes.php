<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Payment Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian Maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PaymentStatusCodes
{
    const AUTHORISED = 'AUTHORISED';
    const DELETED = 'DELETED';

    public $descriptions = [
        self::AUTHORISED => 'Authorised payment',
        self::DELETED => 'Deleted payment',
    ];
}
