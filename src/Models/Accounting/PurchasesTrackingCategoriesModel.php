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
 * Purchases Tracking Categories Model
 *
 * Details on writable properties for Purchases Tracking Categories:
 * https://developer.xero.com/documentation/api/tracking-categories
 */
class PurchasesTrackingCategoriesModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'PurchasesTrackingCategories';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'PurchasesTrackingCategory';

    /**
     *
     * Details on writable properties for PurchasesTrackingCategory:
     * https://developer.xero.com/documentation/api/tracking-categories
     *
     * @var array $fields
     */
    protected $fields = [
        'trackingCategoryName' => [
            'type' => 'string',
            'nullable' => true,
            /*
             * Not sure it is true.
             * I get the mistake: Model "SalesTrackingCategoriesModel" key trackingCategoryName Property is null without nullable permission
             * The same is for trackingOptionName field.
             */
            'readonly' => false,
        ],
        'trackingOptionName' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
    ];
}