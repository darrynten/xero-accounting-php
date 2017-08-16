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

use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\Validation\Validation;
use DarrynTen\Xero\Validation\ModelValidation;

/**
 * This is the base class for all the Xero Models.
 *
 * The Base Model extends this and adds functionality for API interaction
 *
 * It also handles conversion between our Model objects, and JSON that is
 * compliant with the Xero API format.
 *
 * In order to provide ORM type functionality we support re-hydrating any
 * model with its defined JSON fragment.
 */
abstract class StaticBaseModel
{
    use Validation;
    use ModelValidation;

    /**
     * Entity - set automatically
     */
    public $entity = null;

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * @var array $features
     */
    protected $features = false;

    /**
     * A models configuration is stored here
     *
     * @var array $config
     */
    protected $config = null;

    /**
     * String required to detect name of field used as id
     *
     * null means no id field present
     *
     * TODO where is this implemented
     *
     * @var string $idField
     */
    protected $idField  = null;

    /**
     * String required to detect if we need to validate model by types
     *
     * TODO what's actually happening with this?
     *
     * @var string $typeField
     */
    protected $typeField  = '';

    /**
     * A models fields are stored here
     *
     * @var array $fieldsData
     */
    protected $fieldsData = [];

    /**
     * Make a new model
     *
     * @param array $config The config for the model
     */
    public function __construct(array $config)
    {
        $this->entity = $this->getClassName(static::class);
        $this->config = $config;
    }

    /**
     * Extracts className from path A\B\C\ClassName
     *
     * TODO this code exists elsewhere too
     *
     * @param string $classPath Full path to the class
     */
    private function getClassName(string $class)
    {
        $classPath = explode('\\', $class);
        $className = $classPath[count($classPath) - 1];
        return $className;
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

        // At this stage we would be dealing with a related Model
        $class = $this->getModelWithNamespace($config['type']);

         // So if the class doesn't exist, throw
        if (!class_exists($class)) {
             $this->throwException(ModelException::UNEXPECTED_PREPARE_CLASS, sprintf(
                 'Received unexpected namespaced class "%s" when preparing an object row',
                 $class
             ));
        }

        // And finally return an Object representation of the related Model
        return $value->toObject();
    }


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
    protected function getRemoteKey($localKey)
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
    protected function toObject()
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
       // If it's null and it's allowed to be null
        if (is_null($resultItem) && ($config['nullable'] === true)) {
            return null;
        }

       // At this stage, any type is going to be a model that needs to be loaded
        $class = $this->getModelWithNamespace($config['type']);

        // So if the class doesn't exist, throw
        if (!class_exists($class)) {
            $this->throwException(ModelException::PROPERTY_WITHOUT_CLASS, sprintf(
                'Received namespaced class "%s" when defined type is "%s"',
                $class,
                gettype($resultItem),
                $resultItem
            ));
        }

        // Make a new instance of the class and load the item
        $instance = new $class($this->config);
        $instance->loadResult($resultItem);

        // Return that instance
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
}
