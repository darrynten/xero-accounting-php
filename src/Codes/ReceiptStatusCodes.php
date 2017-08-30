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
        self::DRAFT => 'Draft receipt',
        self::SUBMITTED => 'Submitted receipt ',
        self::AUTHORISED => 'Authorised receipt',
        self::DECLINED => 'Declined receipt',
    ];
}
