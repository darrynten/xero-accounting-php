<?php

namespace DarrynTen\Xero\Validation;

use DarrynTen\Xero\Exception\ValidationException;

/**
 * Xero Validation Helper
 *
 * @category Configuration
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */
trait Validation
{
    protected $validPrimitiveTypes = [
        'string',
        'integer',
        'boolean',
        'double',
    ];
    /**
     * Check if the type matches a valid primitive
     *
     * @var string $item The item to check
     * @var string $definedType The type that the item should be
     *
     * @return boolean
     */
    public function isValidPrimitive($item, $definedType)
    {
        $itemType = gettype($item);

        if (in_array($itemType, $this->validPrimitiveTypes) && ($itemType === $definedType)) {
            return true;
        }

        /**
         * We sometimes receive integers as strings
         *
         * This checks for that one particular condition with a strict
         * regex
         */
        if ($itemType === 'string' && $definedType === 'integer') {
            if (preg_match('/[0-9]{1,}/', $item)) {
                return true;
            }
        }

        /**
         * We sometimes receive booleans as strings
         *
         * This checks for that one particular condition with a strict
         * regex
         */
        if ($itemType === 'string' && $definedType === 'boolean') {
            if ($item === 'true') {
                return true;
            }

            if ($item === 'false') {
                return true;
            }
        }

        return false;
    }

    /**
     * Validates a regex
     *
     * @param string $value
     * @param string $regex
     *
     * @throws ValidationException
     *
     */
    public function validateRegex($value, $regex)
    {
        if (!preg_match($regex, $value)) {
            throw new ValidationException(
                ValidationException::STRING_REGEX_MISMATCH,
                sprintf('value %s failed to validate', $value)
            );
        }
    }

    /**
     * Validates a value is within a given range.
     *
     * The value can either be an integer, which checks an inclusive range,
     * or can be a string, which checks length.
     *
     * @param string|integer $value
     * @param integer $min
     * @param integer $max
     *
     * @throws ValidationException
     */
    public function validateRange($value, $min, $max)
    {
        if (gettype($value) === 'integer') {
            if (($value < $min) || ($value > $max)) {
                throw new ValidationException(
                    ValidationException::INTEGER_OUT_OF_RANGE,
                    sprintf(
                        'value %s out of min(%s) max(%s)',
                        $value,
                        $min,
                        $max
                    )
                );
            }

            return;
        }

        if (gettype($value) === 'string') {
            // TODO should this be multi-byte?
            if ((strlen($value) <= $min) || (strlen($value) > $max)) {
                throw new ValidationException(
                    ValidationException::STRING_LENGTH_OUT_OF_RANGE,
                    sprintf(
                        'value %s out of min(%s) max(%s)',
                        $value,
                        $min,
                        $max
                    )
                );
            }

            return;
        }

        // Unknown type for validation
        throw new ValidationException(
            ValidationException::VALIDATION_TYPE_ERROR,
            sprintf(
                'value %s is type %s',
                $value,
                gettype($value)
            )
        );
    }
}
