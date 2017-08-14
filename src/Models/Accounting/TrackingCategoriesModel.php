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
 * Tracking Categories Model
 *
 * Details on writable properties for Tracking Categories:
 * https://developer.xero.com/documentation/api/tracking-categories
 */
class TrackingCategoriesModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'TrackingCategories';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'TrackingCategory';

    /**
     *
     * Details on writable properties for TrackingCategory:
     * https://developer.xero.com/documentation/api/tracking-categories
     *
     * @var array $fields
     */
    protected $fields = [
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 100,
        ],
        'status' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'trackingCategoryID' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'regex' => '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
        ],
        'trackingOptionID' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'regex' => '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
        ],
        'options' => [
            'type' => 'TrackingCategoriesOptionsModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'option' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}