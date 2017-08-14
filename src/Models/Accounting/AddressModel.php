<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Igor Sergiichuk <igorsergiichuk@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Models\Accounting;

use DarrynTen\Xero\BaseModel;

/**
 * Address Model
 *
 * Details on writable properties for Contacts:
 * https://developer.xero.com/documentation/api/contacts
 */
class AddressModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Address';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    public $entity = 'Address';

    /**
     *
     * Details on writable properties for Address:
     * https://developer.xero.com/documentation/api/Types
     *
     * @var array $fields
     */
    protected $fields = [
        'addressType' => [
            'type' => 'string',
            'nullable' =>true,
            'readonly' => false,
        ],
        'addressLine1' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'addressLine2' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'addressLine3' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'addressLine4' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'city' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 255,
        ],
        'region' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 255,
        ],
        'postalCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 50,
        ],
        'country' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 50,
        ],
        'attentionTo' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 50,
        ],
    ];
}
