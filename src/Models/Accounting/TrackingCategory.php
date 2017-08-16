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
class TrackingCategory extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    public $endpoint = 'TrackingCategories';

    /**
     * String required to detect name of field used as id
     *
     * @var string $idField
     */
    protected $idField  = 'trackingCategoryID';

    // TODO include archived
    // https://developer.xero.com/documentation/api/tracking-categories

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
        'options' => [
            'type' => 'TrackingCategoryOption',
            'nullable' => false,
            'readonly' => false,
            'collection' => true,
        ],
    ];

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * @var array $features
     */
    protected $features = [
        'all' => true,
        'get' => true,
        'create' => true,
        'update' => true,
        'delete' => false,
        'order' => true,
        'filter' => true,
    ];
}
