<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Invoice Status Codes (also covers credit note status codes)
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian Maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class InvoiceStatusCodes
{
    const DRAFT = 'DRAFT';
    const SUBMITTED = 'SUBMITTED';
    const DELETED = 'DELETED';
    const AUTHORISED = 'AUTHORISED';
    const PAID = 'PAID';
    const VOIDED = 'VOIDED';

    public $descriptions = [
        self::DRAFT => 'Draft invoice',
        self::SUBMITTED => 'Invoice awaiting approval ',
        self::DELETED => 'Deleted invoice',
        self::AUTHORISED => 'Approved invoice',
        self::PAID => 'Paid invoice  ',
        self::VOIDED => 'Voided invoice',
    ];
}
