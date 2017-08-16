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
class PurchasesTrackingCategory extends BaseModel
{
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
             * TODO
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
