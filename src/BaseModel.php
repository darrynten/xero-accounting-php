<?php
/**
 * Xero Library - Base Model
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 * @version  PHP 7+
 *
 * TODO Split this file it's getting too big
 */

namespace DarrynTen\Xero;

use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\Validation;

/**
 * This is the base class for all the Xero Models.
 *
 * This class covers all/get/save/delete/update calls for all models that require it.
 *
 * It also handles conversion between our Model objects, and JSON that is
 * compliant with the Xero API format.
 *
 * In order to provide ORM type functionality we support re-hydrating any
 * model with its defined JSON fragment.
 */
abstract class BaseModel
{
    use Validation;

    /**
     * API Endpoint
     *
     * Null means non interactable
     */
    public $endpoint = null;

    /**
     * A request object
     *
     * TODO this should be refactored out
     */
    protected $request;

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * @var array $features
     */
    protected $features = [
        'all' => false,
        'get' => false,
        'create' => false,
        'update' => false,
        /**
         * Non-system accounts and accounts not used on transactions
         * can be deleted using the delete method. If an account is
         * not able to be deleted you can update the status to ARCHIVED
         */
        'delete' => false,
        'order' => true,
        'filter' => true,
    ];

    /**
     * String required to detect name of field used as id
     *
     * null means no id field present
     *
     * @var string $idField
     */
    protected $idField  = null;

    /**
     * String required to detect if we need to validate model by types
     *
     * @var string $typeField
     */
    protected $typeField  = '';

    /**
     * A models configuration is stored here
     *
     * @var array $config
     */
    protected $config = null;

    /**
     * A models fields are stored here
     *
     * @var array $fieldsData
     */
    private $fieldsData = [];

    /**
     * Make a new model
     *
     * Setup a request handler and bind the config
     *
     * @param array $config The config for the model
     */
    public function __construct(array $config)
    {
        // TODO can't be spawning a million of these and passing in
        // config the whole time
        // TODO switch to Xero-Auth
        $this->request = new RequestHandler($config);
        $this->config = $config;
    }

    /**
     * Ensure attempted sets are valid
     *
     * The key has to be defined in the field map
     * The key needs to be checked if it is read only
     * The key cannot set null if it is not nullable
     * The value for the key must pass validation
     *
     * @var string $key The property
     * @var mixed $value The desired value
     */
    public function __set($key, $value)
    {
        $this->checkDefined($key, $value);
        $this->checkReadOnly($key, $value);
        $this->checkNullable($key, $value);
        $this->checkValidation($key, $value);

        $this->fieldsData[$key] = $value;
    }

    /**
     * __get
     *
     * @param string $key Desired property
     *
     * @thows ModelException
     */
    public function __get($key)
    {
        if (!array_key_exists($key, $this->fields)) {
            $this->throwException(ModelException::GETTING_UNDEFINED_PROPERTY, sprintf('key %s', $key));
        }

        // there is some data loaded so we return it
        if (array_key_exists($key, $this->fieldsData)) {
            return $this->fieldsData[$key];
        }

        // there is some default value
        if (array_key_exists('default', $this->fields[$key])) {
            return $this->fields[$key]['default'];
        }

        // Accessing $obj->key when no default data is set returns null
        // so we return it as default value for any described but not loaded property
        return null;
    }

    /**
     * Returns 'all' of something
     *
     * All methods that retrieve a collection follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * NB: Does not load an account into the class! Returns a raw representation
     * from the Xero API.
     *
     * $account = new Account;
     * $allAccounts = $account->all();
     *
     * @param array $parameters for sort and filter implementation
     *
     * @return ModelCollection A collection of entities
     */
    public function all(array $parameters = [])
    {
        if (!$this->features['all']) {
            $this->throwException(ModelException::NO_GET_ALL_SUPPORT);
        }

        if ($parameters) {
            $parameters = $this->prepareGetQueryParams($parameters);
        }

        $results = $this->request->request('GET', $this->endpoint, null, $parameters);

        return new ModelCollection(static::class, $this->config, $results->{$this->entity});
    }

    /**
     * Returns 'one' of something
     *
     * All methods that retrieve a single entity follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * $account = new Account;
     * $account->get('297c2dc5-cc47-4afd-8ec8-74990b8761e9');
     *
     * @return object A single entity
     */
    public function get(string $id)
    {
        if (!$this->features['get']) {
            $this->throwException(ModelException::NO_GET_ONE_SUPPORT, sprintf('id %s', $id));
        }

        $result = $this->request->request('GET', $this->endpoint, $id);

        $this->loadResult($result->{$this->entity});
    }

    /**
     * Returns ModelCollection of something based on given ids
     *
     * $account = new Account;
     * $account->getByIds(['297c2dc5-cc47-4afd-8ec8-74990b8761e9', '297c2dc5-cc47-4afd-8ec8-74990b8761e9']);
     *
     * @param array $ids
     *
     * @return ModelCollection A collection of entities
     */
    public function getByIds(array $ids)
    {
        if (!$this->features['get']) {
            $this->throwException(
                ModelException::NO_GET_ONE_SUPPORT,
                sprintf(
                    'id %s',
                    implode(', ', ($ids))
                )
            );
        }

        $results = [];
        foreach ($ids as $id) {
            $result = $this->request->request('GET', $this->endpoint, $id)->{$this->entity};
            array_push($results, $result);
        }

        return new ModelCollection(static::class, $this->config, $results);
    }

    /**
     * Delete an entity
     *
     * All methods that retrieve a single entity follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * $account = new Account;
     * $account->delete('297c2dc5-cc47-4afd-8ec8-74990b8761e9');
     *
     * @param string $id The ID to delete
     *
     * @return void
     */
    public function delete(string $id)
    {
        if (!$this->features['delete']) {
            $this->throwException(ModelException::NO_DELETE_SUPPORT, sprintf('id %s', $id));
        }

        // TODO Response handle?
        $this->request->request('DELETE', $this->endpoint, $id);
    }

    /**
     * Submits a save call to Xero
     *
     * @return BaseModel
     */
    public function create()
    {
        if (!$this->features['create']) {
            $this->throwException(ModelException::NO_CREATE_SUPPORT);
        }
        $this->validateModel();
        $this->validateMinimumFieldsRequiredForCreate();

        $data = $this->toObject();
        $xml = $this->generateValidXmlFromArray($data);
        $data = $this->request->request('PUT', $this->endpoint, 'Save', ['body' => $xml]);
        /**
         * we do not need to verify results here because loadResult will throw exception
         * in case of invalid body
         * If we reach this string then we expect that API returned valid body with response code 200
         * otherwise ApiException was thrown and this line can not be reached
         */
        $this->loadResult($data->{$this->entity});

        return $this;
    }

    /**
     * Submits a update call to Xero
     *
     * @return BaseModel
     */
    public function update()
    {
        if (!$this->features['update']) {
            $this->throwException(ModelException::NO_UPDATE_SUPPORT);
        }
        if (!$this->{$this->idField}) {
            $this->throwException(ModelException::ID_MISSING_FOR_UPDATE);
        }
        $this->validateModel();

        $id = $this->{$this->idField};
        $data = $this->toObject();
        $xml = $this->generateValidXmlFromArray($data);

        $data = $this->request->request('POST', $this->endpoint, $id, ['body' => $xml]);
        /**
         * we do not need to verify results here because loadResult will throw exception
         * in case of invalid body
         * If we reach this string then we expect that API returned valid body with response code 200
         * otherwise ApiException was thrown and this line can not be reached
         */
        $this->loadResult($data->{$this->entity});

        return $this;
    }

    /**
     * Returns a JSON representation of the Model
     *
     * Conforms 100% to Xero responses and can load into other copies
     *
     * @return string JSON representation of the Model
     */
    public function toJson()
    {
        return json_encode($this->toObject(), JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * Prepare an object row for export
     *
     * @var string $key The objects key
     * @var array $config The configuration for the object field
     *
     * @return mixed|null|void
     */
    private function prepareObjectRow($key, $config)
    {
        $value = $this->__get($key);

        // If null and allowed to be null, return null
        if (is_null($value) && $this->fields[$key]['nullable']) {
            return null;
        }

        // If null and can't be null then throw
        if (is_null($value) && !$this->fields[$key]['nullable']) {
            $this->throwException(ModelException::NULL_WITHOUT_NULLABLE, sprintf('key %s', $key));
        }

        // If it's a valid primitive
        if ($this->isValidPrimitive($value, $config['type'])) {
            return $this->$key;
        }

        // If it's a date we return a valid format
        if ($config['type'] === 'DateTime') {
            return $value->format('Y-m-d');
        }

        if (isset($config['collection']) && $config['collection'] === true) {
            return $this->prepareModelCollection($config, $value);
        }
//
//        // At this stage we would be dealing with a related Model
//        $class = $this->getModelWithNamespace($config['type']);
//
//        // So if the class doesn't exist, throw
//        if (!class_exists($class)) {
//            $this->throwException(ModelException::UNEXPECTED_PREPARE_CLASS, sprintf(
//                'Received unexpected namespaced class "%s" when preparing an object row',
//                $class
//            ));
//        }
//
//        // And finally return an Object representation of the related Model
//        return $value->toObject();
    }// @codeCoverageIgnore


    /**
     * Turns the model collection into an array of models
     *
     * @param array $config The config for the model
     * @param ModelCollection $value Collection which is converted into array
     * @return array
     */
    private function prepareModelCollection(array $config, ModelCollection $value)
    {
        $class = $this->getModelWithNamespace($config['type']);
        if (!class_exists($class)) {
            $this->throwException(ModelException::COLLECTION_WITHOUT_CLASS, sprintf(
                'Class "%s" for collection does not exist',
                $class
            ));
        }
        $rows = [];
        foreach ($value->results as $result) {
            $rows[] = $result->toObject();
        }
        return $rows;
    }


    /**
     * Switches between our id format and xeros id format
     *
     * Sage is PascalCase ours is camelCase
     *
     * @var string $localKey
     *
     * @return string Remote key
     */
    private function getRemoteKey($localKey)
    {
        $remoteKey = ucfirst($localKey);

        return $remoteKey;
    }


    /**
     * Turns the model into an object for exporting.
     *
     * Loops through valid fields and exports only those, so as to match the
     * Xero API responses.
     *
     * @return array
     */
    private function toObject()
    {
        $result = [];
        foreach ($this->fields as $localKey => $config) {
            $remoteKey = $this->getRemoteKey($localKey);
            $result[$remoteKey] = $this->prepareObjectRow($localKey, $config);
        }

        return $result;
    }

    /**
     * Runs a value through a basic type fix/check.
     *
     * Only thing it does right now is a very strict conversion of
     * a string representation of an integer to the integer version
     *
     * @param mixed $value The thing to check
     * @param string $value The desired type
     *
     * @return mixed Original thing, cast if needed
     */
    private function typeFix($value, $desiredType)
    {
        $itemType = gettype($value);

        /**
         * This typecast may only happen if you want an integer
         * and the type is a string that has only numbers, and
         * at least one number.
         */
        if ($itemType === 'string' && $desiredType === 'integer') {
            if (preg_match('/[0-9]{1,}/', $value)) {
                return (integer)$value;
            }
        }

        /**
         * This typecast may only happen if you want a boolean
         * and the type a string that is either literally true or false
         */
        if ($itemType === 'string' && $desiredType === 'boolean') {
            if ($value === 'true') {
                return true;
            }

            if ($value === 'false') {
                return false;
            }
        }

        return $value;
    }

    /**
     * Process an item during loading a payload
     *
     * @var $resultItem The item to load
     * @var $config The configuration for the item
     *
     * @return mixed
     */
    private function processResultItem($resultItem, $config)
    {
        if ($this->isValidPrimitive($resultItem, $config['type'])) {
            return $this->typeFix($resultItem, $config['type']);
        }

        // If it's a date we return a new DateTime object
        if ($config['type'] === \DateTime::class) {
            return new \DateTime($resultItem);
        }

        if (isset($config['collection']) && $config['collection'] === true) {

            $class = $this->getModelWithNamespace($config['type']);
            if (!class_exists($class)) {
                $this->throwException(ModelException::COLLECTION_WITHOUT_CLASS, sprintf(
                    'class "%s"',
                    $class
                ));
            }
            return new ModelCollection($class, $this->config, $resultItem);
        }
//        // If it's null and it's allowed to be null
        if (is_null($resultItem) && ($config['nullable'] === true)) {
            return null;
        }

//        // At this stage, any type is going to be a model that needs to be loaded
        $class = $this->getModelWithNamespace($config['type']);

        // (var_dump('=================================---------'));
        // (var_dump('CONFIG: ',$config));
        // (var_dump('RESULTITEM: ',$resultItem));
        // (var_dump('CLASS: ',$class));
        // (var_dump('=================================---------'));
//
//        // So if the class doesn't exist, throw
        if (!class_exists($class)) {
            $this->throwException(ModelException::PROPERTY_WITHOUT_CLASS, sprintf(
                'Received namespaced class "%s" when defined type is "%s"',
                $class,
                gettype($resultItem),
                $resultItem
            ));
        }
//
//         // Make a new instance of the class and load the item
        $instance = new $class($this->config);
        $instance->loadResult($resultItem);
//
//         // Return that instance
        return $instance;
    }

    /**
     * Loads up a result from an object
     *
     * The object can be created by json_decode of a Xero response
     *
     * Used for restoring and loading related models
     *
     * @var object $result A raw object representation
     */
    public function loadResult(\stdClass $result)
    {
        $result = $this->removeSkippedResults($result);

        // We only care about entires that are defined in the model
        foreach ($this->fields as $key => $config) {
            $remoteKey = $this->getRemoteKey($key);
            // If the payload is missing an item
            if (!property_exists($result, $remoteKey)) {
                if (!array_key_exists('required', $config)) {
                    continue;
                }
                $this->throwException(ModelException::INVALID_LOAD_RESULT_PAYLOAD, sprintf(
                    'Defined key "%s" not present in payload',
                    $key
                ));
            }

            // (var_dump('=================================---------'));
            // (var_dump('CONFIG: ',$config));
            // (var_dump('RESULT: ',$result));
            // (var_dump('REMOTE: ',$remoteKey));
            // (var_dump('REMOTE VALUE: ',$result->{$remoteKey}));
            // (var_dump('=================================---------'));
            $value = $this->processResultItem($result->{$remoteKey}, $config);

            // This is similar to __set but it can fill read only fields
            $this->checkDefined($key, $value);
            $this->checkNullable($key, $value);
            $this->checkValidation($key, $value);

            $this->fieldsData[$key] = $value;
        }

        if ($this->typeField) {
            $this->validateModelByType();
        }
    }

    /**
     * Ensure the field is defined
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkDefined($key, $value)
    {
        if (!array_key_exists($key, $this->fields)) {
            $this->throwException(ModelException::SETTING_UNDEFINED_PROPERTY, sprintf('key %s value %s', $key, $value));
        }
    }

    /**
     * Check if the field is read only
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkReadOnly($key, $value)
    {
        if ($this->fields[$key]['readonly']) {
            $this->throwException(ModelException::SETTING_READ_ONLY_PROPERTY, sprintf('key %s value %s', $key, $value));
        }
    }

    /**
     * Check if the field can be set to null
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkNullable($key, $value)
    {
        if (!$this->fields[$key]['nullable'] && is_null($value)) {
            $this->throwException(ModelException::NULL_WITHOUT_NULLABLE, sprintf('attempting to nullify key %s', $key));
        }
    }

    /**
     * Check min-max and regex validation
     *
     * @var string $key
     * @var string|integer $value
     * @thows ModelException
     */
    private function checkValidation($key, $value)
    {
        // If it is and can be null
        if (is_null($value) && ($this->fields[$key]['nullable'] === true)) {
            return;
        }

        // If values have a defined min/max then validate
        if ((array_key_exists('min', $this->fields[$key])) && (array_key_exists('max', $this->fields[$key]))) {
            $this->validateRange($value, $this->fields[$key]['min'], $this->fields[$key]['max']);
        }

        // If values have a defined regex then validate
        if (array_key_exists('regex', $this->fields[$key])) {
            $this->validateRegex($value, $this->fields[$key]['regex']);
        }
    }

    /**
     * Properly handles and throws ModelExceptions
     *
     * @var integer $code The exception code
     * @var string $message Any additional information
     *
     * @throws ModelException
     */
    public function throwException($code, $message = '')
    {
        throw new ModelException((new \ReflectionClass($this))->getShortName(), $code, $message);
    }

    /**
     * Used to determine namespace for related models
     *
     * @var string Name of the model
     *
     * @return string The full namespace for a Model
     * TODO Accounting, Payroll etc, or remove Accounting?
     */
    private function getModelWithNamespace(string $model)
    {
        return sprintf(
            '%s\Models\Accounting\%s',
            __NAMESPACE__,
            $model
        );
    }

    /**
     * Used to skip empty values after parsing from XML
     *
     * @param \stdClass $results
     *
     * @return \stdClass
     */
    private function removeSkippedResults(\stdClass $results)
    {
        foreach ((array) $results as $field => $value) {
            if ($value instanceof \stdClass && empty((array) $value)) {
                $results->$field = null;
            }
        }

        return $results;
    }

    /**
     * Used to generate XML nodes from array
     *
     * @param $array
     *
     * @return string
     */
    private function generateXmlFromArray($array)
    {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                if ($value) {
                    $xml .= '<' . $key . '>' . "\n" . $this->generateXmlFromArray($value) . '</' . $key . '>' . "\n";
                }
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
        }

        return $xml;
    }

    /**
     *
     * Used to generate XML nodes from array
     *
     * @param $array
     *
     * TODO what is difference between generate valid xml and the
     * other method?
     *
     * @return string
     */
    private function generateValidXmlFromArray(array $array)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

        $xml .= '<' . $this->entity . '>' . "\n";
        $xml .= $this->generateXmlFromArray($array);
        $xml .= '</' . $this->entity . '>' . "\n";

        return $xml;
    }

    /**
     * Used to generate right filters for query from raw parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function prepareGetQueryParams(array  $parameters) : array
    {
        $queryParams = [];
        if (array_key_exists('order', $parameters)) {
            if (!$this->features['order']) {
                $this->throwException(ModelException::NO_SORT_SUPPORT);
            }
            if (!array_key_exists('field', $parameters['order'])) {
                $this->throwException(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);
            }
            if (!in_array($parameters['order']['field'], array_keys($this->fields))) {
                $this->throwException(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);
            }

            $queryParams['order'] = $parameters['order']['field'];
            if (array_key_exists('direction', $parameters['order']) &&
                in_array($parameters['order']['direction'], ['ASC','DESC'])) {
                $queryParams['order'] .= sprintf('+%s', $parameters['order']['direction']);
            }
        }

        if (array_key_exists('filter', $parameters)) {
            if (!$this->features['filter']) {
                $this->throwException(ModelException::NO_FILTER_SUPPORT);
            }
            foreach ($parameters['filter'] as $key => $value) {
                if ($value) {
                    if (!in_array($key, array_keys($this->fields))) {
                        $this->throwException(ModelException::TRYING_FILTER_BY_UNKNOWN_FIELD);
                    }
                    $preparedKey = sprintf('%ss', $this->getRemoteKey($key));
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $queryParams[$preparedKey] = $value;
                }
            }
        }

        return $queryParams;
    }

    /**
     * Used to cast values as we get strings from XML
     *
     * @param string $expectedType
     * @param $value
     *
     * @return int|mixed
     */
    private function castToType(string $expectedType, $value)
    {
        if ($expectedType === 'integer') {
            return (int) $value;
        }

        if ($expectedType === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }

    /**
     * Validates all required properties in model
     */
    public function validateModel()
    {
        foreach ($this->fields as $key => $config) {
            if (!array_key_exists($key, $this->fieldsData) &&
                array_key_exists('required', $config)
            ) {
                $this->throwException(ModelException::REQUIRED_PROPERTY_MISSING, sprintf(
                    'Defined key "%s" not present in model',
                    $key
                ));
            }
        }

        if ($this->typeField) {
            $this->validateModelByType();
        }
    }

    /**
     * Validate model properties by model type
     *
     * TODO not sure what's happening here
     *
     * @throws ModelException
     */
    private function validateModelByType()
    {
        foreach ($this->fields as $key => $config) {
            if (array_key_exists('only', $config)) {
                //property exist and not allowed
                if (array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] !== $config['only']['type']
                ) {
                    $this->throwException(
                        ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
                //property not exists but required
                if (!array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] === $config['only']['type'] &&
                    $config['only']['required']
                ) {
                    $this->throwException(
                        ModelException::REQUIRED_PROPERTY_MISSING_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
            }

            if (array_key_exists('except', $config)) {
                if (array_key_exists($key, $this->fieldsData) &&
                    $this->fieldsData[$this->typeField] === $config['except']['type']
                ) {
                    $this->throwException(
                        ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE,
                        sprintf('property %s', $key)
                    );
                }
            }
        }
    }

    /**
     * Validate that model has minimum amount of fields for create operation
     *
     * @throws ModelException
     */
    private function validateMinimumFieldsRequiredForCreate()
    {
        foreach ($this->fields as $key => $config) {
            if (array_key_exists('create', $config) &&
                !array_key_exists($key, $this->fieldsData)
            ) {
                if (array_key_exists('exceptType', $config['create']) &&
                    $this->fieldsData[$this->typeField] === $config['create']['exceptType']
                ) {
                    continue;
                }

                if (array_key_exists('onlyType', $config['create']) &&
                    $this->fieldsData[$this->typeField] !== $config['create']['onlyType']
                ) {
                    continue;
                }

                $this->throwException(
                    ModelException::REQUIRED_PROPERTY_MISSING_FOR_CREATE,
                    sprintf('property %s', $key)
                );
            }
        }
    }
}
