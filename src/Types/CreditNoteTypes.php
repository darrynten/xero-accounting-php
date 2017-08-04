<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Credit Note Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class CreditNoteTypes
{
    const ACCPAYCREDIT = 'ACCPAYCREDIT';
    const ACCRECCREDIT = 'ACCRECCREDIT';

    public $descriptions = [
        self::ACCPAYCREDIT => 'An Accounts Payable(supplier) Credit Note',
        self::ACCRECCREDIT => 'An Account Receivable(customer) Credit Note',
    ];
}
