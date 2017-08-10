<?php

namespace DarrynTen\Xero\Types;

/**
 * Xero User Roles Types
 *
 * @category Types
 * @package  XeroPHP
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class UserRolesTypes
{
    const READONLY = 'READONLY';
    const INVOICEONLY = 'INVOICEONLY';
    const STANDARD = 'STANDARD';
    const FINANCIALADVISER = 'FINANCIALADVISER';
    const MANAGEDCLIENT = 'MANAGEDCLIENT';
    const CASHBOOKCLIENT = 'CASHBOOKCLIENT';

    public $descriptions = [
        self::READONLY => 'Read only user',
        self::INVOICEONLY => 'Invoice only user',
        self::STANDARD => 'Standard user',
        self::FINANCIALADVISER => 'Financial adviser role',
        self::MANAGEDCLIENT => 'Managed client role',
        self::CASHBOOKCLIENT => 'Cashbook client role',
    ];
}
