<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Invoice Status Codes
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
        self::DRAFT => 'A Draft Invoice (default)',
        self::SUBMITTED => 'An Awaiting Approval Invoice',
        self::DELETED => 'A Deleted Invoice',
        self::AUTHORISED => 'An Invoice that is Approved and Awaiting Payment OR partially paid',
        self::PAID => 'An Invoice that is completely Paid',
        self::VOIDED => 'A Voided Invoice',
    ];
}
