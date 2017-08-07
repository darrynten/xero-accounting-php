<?php

namespace DarrynTen\Xero\Codes;

/**
 * Xero Contact Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Brian Maiyo <kiproping@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class ContactStatusCodes
{
    const ACTIVE = 'ACTIVE';
    const ARCHIVED = 'ARCHIVED';

    public $descriptions = [
        self::ACTIVE => 'Active account',
        self::ARCHIVED => 'Archived account',
    ];
}
