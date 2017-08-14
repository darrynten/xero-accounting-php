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
 * BrandingThemes Model
 *
 * Details on writable properties for BrandingThemes:
 * https://developer.xero.com/documentation/api/branding-themes
 */
class BrandingThemesModel extends BaseModel
{
    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'BrandingThemes';

    /**
     * String required to get right property from \stdObj after parsing from xml
     * @var string $entity
     */
    protected $entity = 'BrandingTheme';

    /**
     *
     * Details on writable properties for BrandingThemes:
     * https://developer.xero.com/documentation/api/branding-themes
     *
     * @var array $fields
     */
    protected $fields = [
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
        ],
        'sortOrder' => [
            'type' => 'integer',
            'nullable' => false,
            'readonly' => false,
            'default' => 0,
        ],
        'brandingThemeID' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
            'regex' => "/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/",
        ],
        'createdDateUTC' => [
            'type' => 'DateTime',
            'nullable' => false,
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
        'create' => false,
        'update' => false,
        'delete' => false,
        'order' => false,
        'filter' => false,
    ];
}
