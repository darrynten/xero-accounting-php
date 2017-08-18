<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Receipt Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class ReceiptStatusCodes
{
    const DRAFT = 'DRAFT';
    const SUBMITTED = 'SUBMITTED';
    const AUTHORISED = 'AUTHORISED';
    const DECLINED = 'DECLINED';

    public $descriptions = [
        self::DRAFT => 'A draft receipt (default)',
        self::SUBMITTED => 'Receipt has been submitted as part of an expense claim',
        self::AUTHORISED => 'Receipt has been authorised in the Xero app',
        self::DECLINED => 'Receipt has been declined in the Xero app',
    ];
}
