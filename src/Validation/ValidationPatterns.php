<?php

namespace DarrynTen\Xero\Validation;

/**
 * Xero Validation Patterns
 *
 * @category Validation
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-accounting-php/LICENSE>
 * @link     https://github.com/darrynten/xero-accounting-php
 */
class ValidationPatterns
{
    const GUID = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
    const ALPHANUMERIC_DASH_UNDERSCORE = '/^[0-9a-zA-Z-_]+$/';

    public $descriptions = [
        self::GUID => 'Globally Unique Identifier',
        self::ALPHANUMERIC_DASH_UNDERSCORE => 'Alphanumeric String incl Dash `-` and Underscore `_`',
    ];
}
