<?php

namespace DarrynTen\Xero\Validation;

use DarrynTen\Xero\Exception\ModelException;

/**
 * Xero Model Validation Helper
 *
 * @category Validation
 * @package  XeroPHP
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */
trait ModelValidation
{
    /**
     * Ensure the field is defined
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkDefined($key, $value)
    {
        if (!array_key_exists($key, $this->fields)) {
            $this->throwException(ModelException::SETTING_UNDEFINED_PROPERTY, sprintf('key %s value %s', $key, $value));
        }
    }

    /**
     * Check if the field is read only
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkReadOnly($key, $value)
    {
        if ($this->fields[$key]['readonly']) {
            $this->throwException(ModelException::SETTING_READ_ONLY_PROPERTY, sprintf('key %s value %s', $key, $value));
        }
    }

    /**
     * Check if the field can be set to null
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkNullable($key, $value)
    {
        if (!$this->fields[$key]['nullable'] && is_null($value)) {
            $this->throwException(ModelException::NULL_WITHOUT_NULLABLE, sprintf('attempting to nullify key %s', $key));
        }
    }

    /**
     * Check min-max and regex validation
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkValidation($key, $value)
    {
        // If it is and can be null
        if (is_null($value) && ($this->fields[$key]['nullable'] === true)) {
            return;
        }

        // If values have a defined min/max then validate
        if ((array_key_exists('min', $this->fields[$key])) && (array_key_exists('max', $this->fields[$key]))) {
            $this->validateRange($value, $this->fields[$key]['min'], $this->fields[$key]['max']);
        }

        // If values have a defined regex then validate
        if (array_key_exists('regex', $this->fields[$key])) {
            $this->validateRegex($value, $this->fields[$key]['regex']);
        }
    }

    /**
     * Validates all required properties in model
     */
    public function validateModel()
    {
        foreach ($this->fields as $key => $config) {
            if (!array_key_exists($key, $this->fieldsData) &&
                array_key_exists('required', $config)
            ) {
                $this->throwException(ModelException::REQUIRED_PROPERTY_MISSING, sprintf(
                    'Defined key "%s" not present in model',
                    $key
                ));
            }
        }

        if ($this->typeField) {
            $this->validateModelByType();
        }
    }

    /**
     * Validate model properties by model type
     *
     * TODO not sure what's happening here
     *
     * @throws ModelException
     */
    private function validateModelByType()
    {
        foreach ($this->fields as $key => $config) {
            if (array_key_exists('only', $config)) {
                //property exist and not allowed
                if (array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] !== $config['only']['type']
                ) {
                    $this->throwException(
                        ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
                //property not exists but required
                if (!array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] === $config['only']['type'] &&
                    $config['only']['required']
                ) {
                    $this->throwException(
                        ModelException::REQUIRED_PROPERTY_MISSING_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
            }

            if (array_key_exists('except', $config)) {
                if (array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] === $config['except']['type']
                ) {
                    $this->throwException(
                        ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
            }
        }
    }
}
