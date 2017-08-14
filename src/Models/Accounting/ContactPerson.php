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
 * Contact Persons Model
 *
 * Details on writable properties for Contact Persons:
 * https://developer.xero.com/documentation/api/contacts#contact-persons
 */
class ContactPerson extends BaseModel
{
    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    public $entity = 'ContactPerson';

    /**
     *
     * Details on writable properties for Contact Persons:
     * https://developer.xero.com/documentation/api/contacts#contact-persons
     *
     * @var array $fields
     */
    protected $fields = [
        'firstName' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'lastName' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'emailAddress' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'includeInEmails' => [
            'type' => 'boolean',
            'nullable' => false,
            'readonly' => false,
        ],
    ];
}
