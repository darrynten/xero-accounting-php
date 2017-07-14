<?php
/**
 * Xero Library
 *
 * Official Method Documentation:
 * https://developer.xero.com/documentation/api/accounts
 * Replace account with actual link
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 */

namespace DarrynTen\Xero\Models\Accounting;

use DarrynTen\Xero\BaseModel;

/**
 * Example Model
 *
 * Details on writable properties for Example:
 * https://developer.xero.com/documentation/api/accounts
 * Replace 'accounts' with actual link
 */
class Example extends BaseModel
{

    /**
     * The API Endpoint
     *
     * @var string $endpoint
     */
    protected $endpoint = 'Example';

    /**
     * Defines all possible fields.
     *
     * This maps to all the public properties you added above
     *
     * Used by the base class to decide what gets submitted in a save call,
     * validation, etc
     *
     * All must include a type, whether or not it's nullable, and whether or
     * not it's readonly.
     *
     * Documentation -> Model Conversion Rules:
     *
     * - nullable is `true` if the word 'nullable' is in the 'type' column
     * - readonly is `true` if the word 'Read Only/System Generated' is in the Additional Info column.
     * - Type has the following rules
     *   - `date` becomes "DateTime"
     *   - `nullable` is removed, i.e. "nullable integer" is only "integer"
     *   - Multiword linked terms are concatenated, eg:
     *     - "Example Category" becomes "ExampleCategory"
     *     - "Example Type" becomes "ExampleType"
     *
     * NB: Naming convention for keys is to lowercase the first character of the
     * field returned by Xero (they use PascalCase and we use camelCase)
     *
     * `ID` is automatically converted to and from `id`
     *
     * Include a link with Details on writable properties for Example:
     * https://developer.xero.com/documentation/api/accounts
     * Replace 'accounts' with correct link
     *
     * TODO Remove this docblock
     *
     * @var array $fields
     */
    protected $fields = [
        'id' => [
            'type' => 'integer',
            'nullable' => false,
            'readonly' => false,
        ],
        'exampleWithCamel' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => true,
        ],
        'stringRange' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'min' => 1,
            'max' => 10,
        ],
        'stringWithDefault' => [
            'type' => 'string',
            'nullable' => true,
            'readonly' => false,
            'default' => 'some default value',
        ],
        'integerRange' => [
            'type' => 'integer',
            'nullable' => false,
            'readonly' => false,
            'min' => 1,
            'max' => 2147483647,
        ],
        'someBoolean' => [
            'type' => 'boolean',
            'nullable' => false,
            'readonly' => false,
        ],
        'requiredString' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'required' => true,
        ],
        'emailAddress' => [
            'type' => 'string',
            'nullable' => false,
            'readonly' => false,
            'min' => 0,
            'max' => 100,
            'regex' => "/^[A-Za-z0-9,!#\$%&'\*\+\/=\?\^_`\{\|}~-]+(\.[A-Za-z0-9,!#\$%&'\*\+\/=\?\^_`\{\|}~-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.([A-Za-z]{2,})$/"
        ],
        // etc ...
    ];

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * It covers any call documented with these methods
     *
     * GET Model/Get
     * GET Model/Get/{id}
     * DELETE Model/Delete/{id}
     * POST Model/Save
     *
     * You must enable the supported combination of the above 4 calls if they
     * are present in the documentation
     *
     * @var array $features
     */
    protected $features = [
        'all' => false,
        'get' => false,
        'save' => false,
        'delete' => false,
    ];

    /**
     * Any additional construction you may need to do
     *
     * @var array $config
     */
    public function __construct(array $config)
    {
        // Your code here
        // Only include if you need to modify constructor

        // This must always happen
        parent::__construct($config);
    }

    /**
     * Custom Method Example
     *
     * Custom Method Description
     */
    public function anExampleCustomMethod()
    {
        // Write code that deals with all other calls
        return 'Example Model Custom Method Call';
    }
}
