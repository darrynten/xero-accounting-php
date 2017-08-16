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

use DarrynTen\Xero\StaticBaseModel;

/**
 * Sales Tracking Categories Model
 *
 * Details on writable properties for Sales Tracking Categories:
 * https://developer.xero.com/documentation/api/tracking-categories
 */
class SalesTrackingCategory extends StaticBaseModel
{
    /**
     *
     * Details on writable properties for SalesTrackingCategory:
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
             *
             * Find out what default is
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
