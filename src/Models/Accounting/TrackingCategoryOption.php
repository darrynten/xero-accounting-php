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
        'trackingOptionID' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'regex' => '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
        ],
    ];
}