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
 * Tracking Categories Options Model
 *
 * Details on writable properties for Tracking Categories Options:
 * https://developer.xero.com/documentation/api/tracking-categories
 */
class TrackingCategoriesOptionsModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Options';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'Option';

    /**
     *
     * Details on writable properties for Tracking Categories Options:
     * https://developer.xero.com/documentation/api/tracking-categories
     *
     * @var array $fields
     */
    protected $fields = [
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'min' => 1,
            'max' => 100,
        ],
        'status' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}