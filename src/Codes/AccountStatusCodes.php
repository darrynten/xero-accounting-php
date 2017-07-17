<?php

namespace DarrynTen\Xero;

/**
 * Xero Account Status Codes
 *
 * @category Codes
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class AccountStatusCodes
{
    const ACTIVE = 'ACTIVE';
    const ARCHIVED = 'ARCHIVED';

    public $descriptions = [
        self::ACTIVE => 'Active account',
        self::ARCHIVED => 'Archived account',
    ];
}
