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
 * Phones Model
 *
 * Details on writable properties for Phones:
 * https://developer.xero.com/documentation/api/types#phones
 */
class Phone extends BaseModel
{
    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'Phone';

    /**
     *
     * Details on writable properties for Phones:
     * https://developer.xero.com/documentation/api/types#phones
     *
     * @var array $fields
     */
    protected $fields = [
        'phoneType' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            // TODO valid types link
        ],
        'phoneNumber' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 50,
        ],
        'phoneAreaCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 10,
        ],
        'phoneCountryCode' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 20,
        ],
    ];
}
