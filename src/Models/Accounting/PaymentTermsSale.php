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
 * Payment Terms Sales Model
 *
 * Details on writable properties for Payment Terms Sales:
 * https://developer.xero.com/documentation/api/types#PaymentTerms
 */
class PaymentTermsSale extends BaseModel
{
    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    public $entity = 'Sales';

    /**
     *
     * Details on writable properties for PaymentTerms Sales:
     * https://developer.xero.com/documentation/api/types#PaymentTerms
     *
     * @var array $fields
     */
    protected $fields = [
        'day' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'type' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}
