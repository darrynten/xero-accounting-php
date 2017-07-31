<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Account Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class ExpenseClaimStatusCodes
{
    const SUBMITTED = 'SUBMITTED';
    const AUTHORISED = 'AUTHORISED';
    const PAID = 'PAID';


    public $descriptions = [
        self::SUBMITTED => 'Submitted expense claim',
        self::AUTHORISED => 'Authorised expense claim',
        self::PAID => 'Paid expense claim',
    ];
}
