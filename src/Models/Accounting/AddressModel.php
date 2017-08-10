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
 * Contacts Model
 *
 * Details on writable properties for Contacts:
 * https://developer.xero.com/documentation/api/contacts
 */
class AddressTypesModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Addresses';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'Address';

    /**
     *
     * Details on writable properties for Contacts:
     * https://developer.xero.com/documentation/api/Types
     *
     * @var array $fields
     */
    protected $fields = [
        'addressType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'addressLine1' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'city' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'postalCode' => [
            'type' => 'integer',
            'nullable' => true,
            'readonly' => false,
        ],
        'attentionTo' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
    ];
}