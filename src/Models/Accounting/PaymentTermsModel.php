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
 * Payment Terms Model
 *
 * Details on writable properties for Payment Terms:
 * https://developer.xero.com/documentation/api/types#PaymentTerms
 */
class PaymentTermsModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'PaymentTerms';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'PaymentTerms';

    /**
     *
     * Details on writable properties for PaymentTerms:
     * https://developer.xero.com/documentation/api/types#PaymentTerms
     *
     * @var array $fields
     */
    protected $fields = [
        'bills' => [
            'type' => 'PaymentTermsBillsModel',
            'nullable' => false,
            'readonly' => false,
        ],
        'sales' => [
            'type' => 'PaymentTermsSalesModel',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}