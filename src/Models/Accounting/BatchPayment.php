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
 * BatchPayments Model
 *
 * Details on writable properties for BatchPayments:
 * https://developer.xero.com/documentation/api/contacts
 */
class BatchPayment extends BaseModel
{
    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'BatchPayment';

    /**
     *
     * Details on writable properties for BatchPayment:
     * https://developer.xero.com/documentation/api/contacts
     *
     * @var array $fields
     */
    protected $fields = [
        'bankAccountNumber' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'bankAccountName' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'details' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}
