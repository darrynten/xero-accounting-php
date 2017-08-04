<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Invoice Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class InvoiceTypes
{
    const ACCPAY = 'ACCPAY';
    const ACCREC = 'ACCREC';

    public $descriptions = [
        self::ACCPAY => 'A bill - commonly known as a Accounts Payable or supplier invoice',
        self::ACCREC => 'A sales invoice - commonly known as an Accounts Receivable or customer invoice',
    ];
}
