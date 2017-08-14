<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Payment Terms Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class PaymentTermsTypes
{
    const DAYSAFTERBILLDATE = 'DAYSAFTERBILLDATE';
    const DAYSAFTERBILLMONTH = 'DAYSAFTERBILLMONTH';
    const OFCURRENTMONTH = 'OFCURRENTMONTH';
    const OFFOLLOWINGMONTH = 'OFFOLLOWINGMONTH';

    public $descriptions = [
        self::DAYSAFTERBILLDATE => 'Day(s) after bill date',
        self::DAYSAFTERBILLMONTH => 'Day(s) after bill month',
        self::OFCURRENTMONTH => 'Of the current month',
        self::OFFOLLOWINGMONTH => 'Of the following month',
    ];
}
