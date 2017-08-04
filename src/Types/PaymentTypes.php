<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Payment Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PaymentTypes
{
    const ACCRECPAYMENT = 'ACCRECPAYMENT';
    const ACCPAYPAYMENT = 'ACCPAYPAYMENT';
    const ARCREDITPAYMENT = 'ARCREDITPAYMENT';
    const APCREDITPAYMENT = 'APCREDITPAYMENT';
    const APOVERPAYMENTPAYMENT = 'APOVERPAYMENTPAYMENT';
    const AROVERPAYMENTPAYMENT = 'AROVERPAYMENTPAYMENT';
    const ARPREPAYMENTPAYMENT = 'ARPREPAYMENTPAYMENT';
    const APPREPAYMENTPAYMENT = 'APPREPAYMENTPAYMENT';

    public $descriptions = [
        self::ACCRECPAYMENT => 'Accounts Receivable Payment',
        self::ACCPAYPAYMENT => 'Accounts Payable Payment',
        self::ARCREDITPAYMENT => 'Accounts Receivable Credit Payment (Refund)',
        self::APCREDITPAYMENT => 'Accounts Payable Credit Payment (Refund)',
        self::AROVERPAYMENTPAYMENT => 'Accounts Receivable Overpayment Payment (Refund)',
        self::ARPREPAYMENTPAYMENT => 'Accounts Receivable Prepayment Payment (Refund)',
        self::APPREPAYMENTPAYMENT => 'Accounts Payable Prepayment Payment (Refund)',
        self::APOVERPAYMENTPAYMENT => 'Accounts Payable Overpayment Payment (Refund)',
    ];
}
