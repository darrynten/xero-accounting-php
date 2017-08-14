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
 * ContactGroup Model
 *
 * Details on writable properties for ContactGroup:
 * https://developer.xero.com/documentation/api/contactgroups
 */
class ContactGroupModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'ContactGroups';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'ContactGroup';

    /**
     *
     * Details on writable properties for ContactGroups:
     * https://developer.xero.com/documentation/api/contactgroups
     *
     * @var array $fields
     */
    protected $fields = [
        'name' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 500,
        ],
        'status' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
        ],
        'contactGroupID' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'regex' => "/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/",
        ],
        'contacts' => [
            'type' => 'ContactsModel',
            'nullable' => true,
            'readonly' => false,
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
        'delete' => true,
        'order' => true,
        'filter' => true,
        // TODO it also has a features DELETE ALL and RENAME which are not in the list of available
    ];
}