<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero Account Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class AddressTypes
{
    const POBOX = 'POBOX';
    const STREET = 'STREET';
    const DELIVERY = 'DELIVERY';

    public $descriptions = [
        self::POBOX => 'The default mailing address for invoices',
        self::STREET => 'The default street for invoices',
        self::DELIVERY  => 'Read-only. The delivery address of the Xero organisation',
    ];
}
