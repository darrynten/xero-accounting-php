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
 * Payment Terms Model
 *
 * Details on writable properties for Payment Terms:
 * https://developer.xero.com/documentation/api/types#PaymentTerms
 */
class PaymentTerm extends StaticBaseModel
{
    /**
     *
     * Details on writable properties for PaymentTerms:
     * https://developer.xero.com/documentation/api/types#PaymentTerms
     *
     * @var array $fields
     */
    protected $fields = [
        'bills' => [
            'type' => 'PaymentTermsBill',
            'nullable' => false,
            'readonly' => false,
        ],
        'sales' => [
            'type' => 'PaymentTermsSale',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}
