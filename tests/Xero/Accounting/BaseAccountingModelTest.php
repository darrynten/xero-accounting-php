<?php

namespace DarrynTen\Xero\Tests\Xero\Accounting;

use DarrynTen\Xero\BaseModel;
use DarrynTen\Xero\Request\RequestHandler;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\ModelCollection;
use DarrynTen\Xero\Exception\ValidationException;

abstract class BaseAccountingModelTest extends \PHPUnit_Framework_TestCase
{
    use HttpMockTrait;

    protected $config = [
        'key' => 'key',
        'endpoint' => '//localhost:8082',
    ];

    public static function setUpBeforeClass()
    {
        static::setUpHttpMockBeforeClass('8082', 'localhost');
    }

    public static function tearDownAfterClass()
    {
        static::tearDownHttpMockAfterClass();
    }

    public function setUp()
    {
        $this->setUpHttpMock();
    }

    public function tearDown()
    {
        $this->tearDownHttpMock();
    }

    /**
     * Extracts className from path A\B\C\ClassName
     *
     * @param string $classPath Full path to the class
     */
    private function getClassName(string $class)
    {
        $classPath = explode('\\', $class);
        $className = $classPath[ count($classPath) - 1];
        return $className;
    }

    /**
     * Verifies that passed $class (as string) is instance of $class
     *
     * @param string $class Full path to the class
     */
    protected function verifyInstanceOf(string $class)
    {
        $request = new $class($this->config);
        $this->assertInstanceOf($class, $request);
    }

    /**
     * Verifies that when we try to set undefined property it throws expected exception
     *
     * @param string $class Full path to the class
     */
    protected function verifySetUndefined(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" key doesNotExist value xyz Attempting to set a property that is not defined in the model");
        $this->expectExceptionCode(ModelException::SETTING_UNDEFINED_PROPERTY);

        $model = new $class($this->config);
        $model->doesNotExist = 'xyz';
    }

    /**
     * Verifies that when we try to get undefined property it throws expected exception
     *
     * @param string $class Full path to the class
     */
    protected function verifyGetUndefined(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" key doesNotExist Attempting to get an undefined property");
        $this->expectExceptionCode(ModelException::GETTING_UNDEFINED_PROPERTY);

        $model = new $class($this->config);
        $throw = $model->doesNotExist;
    }

    /**
     * Verifies that when we try to set property to null and it can not be null it throws expected exception
     *
     * @param string $class Full path to the class
     * @param string $key Valid not nullable field for this class
     */
    protected function verifyCanNotNullify(string $class, string $key)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" attempting to nullify key {$key} Property is null without nullable permission");
        $this->expectExceptionCode(ModelException::NULL_WITHOUT_NULLABLE);

        $model = new $class($this->config);
        $model->{$key} = null;
    }

    /**
     * Verifies that when we try to set property to null and it can be null it does not throw exception
     *
     * @param string $class Full path to the class
     * @param string $key Valid nullable field for this class
     */
    protected function verifyCanNullify(string $class, string $key)
    {
        $className = $this->getClassName($class);

        $model = new $class($this->config);
        $model->{$key} = null;
        $this->assertNull($model->{$key});
    }

    /**
     * Verifies that when we try to load data for model without required fields it throws expected exception
     *
     * @param string $class Full path to the class
     * @param string $key Valid field for this $class (because of the loading logic it should be first field in $fields attribute after 'id'
     */
    protected function verifyBadImport(string $class, string $key)
    {
        $className = $this->getClassName($class);

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" Defined key \"{$key}\" not present in payload A property is missing in the loadResult payload");
        $this->expectExceptionCode(ModelException::INVALID_LOAD_RESULT_PAYLOAD);

        $obj = new \stdClass;
        $obj->ID = 1;
        $model->loadResult($obj);
    }

    /**
     * Verifies that model will throw error when we try method all that not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedAll(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Get all is not supported");
        $this->expectExceptionCode(ModelException::NO_GET_ALL_SUPPORT);

        $model->all();
    }

    /**
     * Verifies that model will throw error when we try method get that not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedGet(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" id some_id Get single is not supported");
        $this->expectExceptionCode(ModelException::NO_GET_ONE_SUPPORT);

        $model->get('some_id');
    }

    /**
     * Verifies that model will throw error when we try method getByIds that not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedGetByIds(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" id some_id Get single is not supported");
        $this->expectExceptionCode(ModelException::NO_GET_ONE_SUPPORT);

        $model->getByIds(['some_id']);
    }

    /**
     * Verifies that model will throw error when we try method delete that not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedDelete(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" id some_id Delete is not supported");
        $this->expectExceptionCode(ModelException::NO_DELETE_SUPPORT);

        $model->delete('some_id');
    }

    /**
     * Verifies that model will throw error when we try method create not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedCreate(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Create is not supported");
        $this->expectExceptionCode(ModelException::NO_CREATE_SUPPORT);

        $model->create();
    }

    /**
     * Verifies that model will throw error when we try method update not supported by model
     *
     * @param string $class Full path to the class
     * @param array $features features of the model
     */
    protected function verifyNotSupportedUpdate(string $class, array $features)
    {
        $className = $this->getClassName($class);
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Update is not supported");
        $this->expectExceptionCode(ModelException::NO_UPDATE_SUPPORT);

        $model->update('some_id');
    }

    /**
     * Verifies that model will throw error when we try apply filter on all() method
     *
     * @param string $class Full path to the class
     */
    protected function verifyNotSupportedFilter(string $class)
    {
        $className = $this->getClassName($class);

        $features = [
            'all' => true,
            'filter' => false,
        ];
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Filter is not supported");
        $this->expectExceptionCode(ModelException::NO_FILTER_SUPPORT);

        $model->all(['filter' => 'some']);
    }

    /**
     * Verifies that model will throw error when we try apply filter on unknown field
     *
     * @param string $class Full path to the class
     */
    protected function verifyFilterByUnknownValue(string $class)
    {
        $className = $this->getClassName($class);

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Unknown property for filtering");
        $this->expectExceptionCode(ModelException::TRYING_FILTER_BY_UNKNOWN_FIELD);

        $model->all(['filter' => ['not_exists' => 'foo']]);
    }

    /**
     * Verifies that model will throw error when we try apply order with wrong parameters
     *
     * @param string $class Full path to the class
     */
    protected function verifyOrderWithWrongParameters(string $class)
    {
        $className = $this->getClassName($class);

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Unknown property for sorting");
        $this->expectExceptionCode(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);

        $model->all(['order' => []]);
    }

    /**
     * Verifies that model will throw error when we try apply order with wrong fieldname
     *
     * @param string $class Full path to the class
     */
    protected function verifyOrderWithUnknownField(string $class)
    {
        $className = $this->getClassName($class);

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Unknown property for sorting");
        $this->expectExceptionCode(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);

        $model->all(['order' => ['field' => 'not_exists']]);
    }

    /**
     * Verifies that model will throw error when we try update on object that not has accountID
     *
     * @param string $class Full path to the class
     */
    protected function verifyIdMissingOnCreate(string $class)
    {
        $className = $this->getClassName($class);
        $fields = [
            'accountID' => [
                'type' => 'string',
                'nullable' => true,
                'readonly' => false,
            ],
            'name' => [
                'type' => 'string',
                'nullable' => false,
                'readonly' => false,
                'required' => true,
                'min' => 0,
                'max' => 150,
            ],
        ];

        $model = $this->injectPropertyInModel($class, 'fields', $fields);
        $model->name = 'some name';
        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Model identifier missing");
        $this->expectExceptionCode(ModelException::ID_MISSING_FOR_UPDATE);

        $model->update();
    }

    /**
     * Verifies that model will throw error when we try update on object that not has accountID
     *
     * @param string $class Full path to the class
     */
    protected function verifyMissingRequiredProperty(string $class)
    {
        $className = $this->getClassName($class);
        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage(
            "Model \"{$className}\" Defined key \"name\" not present in model Required property missing in model"
        );
        $this->expectExceptionCode(ModelException::REQUIRED_PROPERTY_MISSING);

        $model->create();
    }

    /**
     * Verifies that model will throw error when we try apply order on all() method
     *
     * @param string $class Full path to the class
     */
    protected function verifyNotSupportedOrder(string $class)
    {
        $className = $this->getClassName($class);

        $features = [
            'all' => true,
            'order' => false,
        ];
        $model = $this->injectPropertyInModel($class, 'features', $features);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Sort is not supported");
        $this->expectExceptionCode(ModelException::NO_SORT_SUPPORT);

        $model->all(['order' => 'some']);
    }

    /**
     * Verifies that model will throw error when we try toObject method on null field that hasn't nullable attribute
     *
     * @param string $class Full path to the class
     */
    protected function verifyCantBeNull(string $class)
    {
        $className = $this->getClassName($class);
        $fields = [
            'name' => [
                'type' => 'string',
                'nullable' => false,
                'readonly' => false,
                'min' => 0,
                'max' => 150,
            ],
        ];

        $model = $this->injectPropertyInModel($class, 'fields', $fields);
        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" key name Property is null without nullable permission");
        $this->expectExceptionCode(ModelException::NULL_WITHOUT_NULLABLE);

        $model->create();
    }

    /**
     * Verifies that model will throw error when we try to set value with readonly attribute
     *
     * @param string $class Full path to the class
     */
    protected function verifyCantBeWritten(string $class)
    {
        $className = $this->getClassName($class);
        $fields = [
            'accountID' => [
                'type' => 'string',
                'nullable' => true,
                'readonly' => true,
            ]
        ];
        $model = $this->injectPropertyInModel($class, 'fields', $fields);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" key accountID value some_id Attempting to set a read-only property");
        $this->expectExceptionCode(ModelException::SETTING_READ_ONLY_PROPERTY);

        $model->accountID = 'some_id';
    }

    /**
     * Verifies that all fields has expected types, nullable and read only properties
     *
     * @param string $class Full path to the class
     * @param array $attributes
     *      Contains data in the following format
     *      ['name of the key' =>
     *          'type' => 'name of the type, like integer or DateTime',
     *          'nullable' => true, // if field can be null
     *          'readonly' => false // if field is not read only
     *      ]
     */
    protected function verifyAttributes(string $class, array $attributes)
    {
        $model = new $class($this->config);
        $className = $this->getClassName($class);

        // Fields mapping
        $reflect = new ReflectionClass($model);
        $reflectValue = $reflect->getProperty('fields');
        $reflectValue->setAccessible(true);
        $value = $reflectValue->getValue(new $class($this->config));

        $fieldsCount = count($attributes);

        $this->assertCount($fieldsCount, $value);

        foreach ($attributes as $name => $options) {
            $this->verifyIfOptionsAreValid($className, $name, $options);
            $this->verifyCommonAttributes($className, $name, $options, $value);
            $this->verifyMinMaxAttributes($className, $name, $options, $value);
            $this->verifyRequiredAttribute($className, $name, $options, $value);
            $this->verifyRegexAttribute($className, $name, $options, $value);
            $this->verifyDefaultAttribute($className, $name, $options, $value);
        }
    }

    /**
     * Verifies that field $name in $className has only valid options
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     */
    private function verifyIfOptionsAreValid($className, $name, $options)
    {
        $validKeys = array_fill_keys([
            'type', 'nullable', 'readonly', 'default',
            'required', 'min', 'max', 'regex', 'valid', 'only', 'except', 'create'
        ], true);
        foreach (array_keys($options) as $option) {
            if (!isset($validKeys[$option])) {
                throw new \Exception(sprintf('Unable to validate %s for %s, undefined validation', $option, $name));
            }
        }
    }

    /**
     * Verifies that field $name has expected 'type', 'nullable' and 'readonly' fields
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     *      Contains data in the following format
     *      [
     *          'type' => 'name of the type, like integer or DateTime',
     *          'nullable' => true, // if field can be null
     *          'readonly' => false // if field is not read only
     *      ]
     * @param array $value actual field attributes under check
     *      has the same format as $options
     */
    private function verifyCommonAttributes($className, $name, $options, $value)
    {
        $this->assertTrue(is_array($value[$name]));
        $this->assertEquals(
            $options['type'],
            $value[$name]['type'],
            "Model {$className} Key {$name} Expected type {$options['type']} got {$value[$name]['type']}"
        );
        $this->assertEquals('boolean', gettype($value[$name]['nullable']));
        $this->assertEquals('boolean', gettype($value[$name]['readonly']));

        $nullable = $options['nullable'];
        $nullableText = $nullable ? 'true': 'false';
        $nullableOptionText = $value[$name]['nullable'] ? 'true' : 'false';
        $this->assertEquals(
            $nullable,
            $value[$name]['nullable'],
            "Model {$className} Key {$name} Expected nullable to be {$nullableText} got {$nullableOptionText}"
        );

        $readonly = $options['readonly'];
        $readonlyText = $readonly ? 'true' : 'false';
        $readonlyOptionText = $value[$name]['readonly'] ? 'true' : 'false';

        $this->assertEquals(
            $readonly,
            $value[$name]['readonly'],
            "Model {$className} Key {$name} Expected readonly to be {$readonlyText} got {$readonlyOptionText}"
        );
    }

    /**
     * Verifies that field $name has expected min/max attributes (if any)
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     *      Contains data in the following format
     *      [
     *          'min' => 0,
     *          'max' => 10
     *      ]
     * @param array $value actual field attributes under check
     *      has the same format as $options
     */
    private function verifyMinMaxAttributes($className, $name, $options, $value)
    {
        if (isset($options['min']) && isset($options['max'])) {
            if (!($options['type'] === 'integer' || $options['type'] === 'string')) {
                throw new \Exception('You can validate min & max only for integer or string');
            }

            $this->assertTrue(isset($value[$name]['min']), sprintf('"min" is not present for %s', $name));
            $this->assertTrue(isset($value[$name]['max']), sprintf('"max" is not present for %s', $name));

            $this->assertEquals('integer', gettype($value[$name]['max']));
            $this->assertEquals('integer', gettype($value[$name]['min']));

            $this->assertEquals(
                $options['min'],
                $value[$name]['min'],
                sprintf(
                    'Model %s "min" for %s should be %s but got %s',
                    $className,
                    $name,
                    $options['min'],
                    $value[$name]['min']
                )
            );

            $this->assertEquals(
                $options['max'],
                $value[$name]['max'],
                sprintf(
                    'Model %s "max" for %s should be %s but got %s',
                    $className,
                    $name,
                    $options['max'],
                    $value[$name]['max']
                )
            );
        }
    }

    /**
     * Verifies that field $name has required attribute (if any)
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     *      Contains data in the following format
     *      [
     *          'required' => true
     *      ]
     * @param array $value actual field attributes under check
     *      has the same format as $options
     */
    private function verifyRequiredAttribute($className, $name, $options, $value)
    {
        if (isset($options['required'])) {
            if ($options['required'] !== true) {
                throw new \Exception('You can validate only required=true');
            }

            $this->assertTrue(
                isset($value[$name]['required']),
                sprintf('Model %s "required" for %s is not present', $className, $name)
            );

            $this->assertTrue(
                $value[$name]['required'],
                sprintf('Model %s "required" for %s must be true', $className, $name)
            );
        }
    }

    /**
     * Verifies that field $name has valid regex attribute (if any)
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     *      Contains data in the following format
     *      [
     *          'regex' => '/some regex/'
     *      ]
     * @param array $value actual field attributes under check
     *      has the same format as $options
     */
    private function verifyRegexAttribute($className, $name, $options, $value)
    {
        if (isset($options['regex'])) {
            $this->assertTrue(
                isset($value[$name]['regex']),
                sprintf('Model %s "regex" for %s is not present', $className, $name)
            );

            $this->assertEquals($options['regex'], $value[$name]['regex']);
            $success = preg_match($value[$name]['regex'], '');

            if ($success === false) {
                throw \Exception(sprintf('Model %s Failed to execute regex for %s', $className, $name));
            }
        }
    }

    /**
     * Verifies that field $name has valid default attribute (if any)
     *
     * @param string $className name of the class under checking
     * @param string $name name of the attribute
     * @param array $options what we check
     *      Contains data in the following format
     *      [
     *          'default' => 'some default value (string, integer, null, etc.)'
     *      ]
     * @param array $value actual field attributes under check
     *      has the same format as $options
     */
    private function verifyDefaultAttribute($className, $name, $options, $value)
    {
        if (array_key_exists('default', $options)) {
            $this->assertTrue(
                array_key_exists('default', $value[$name]),
                sprintf('Model %s "default" for %s is not present', $className, $name)
            );
            $this->assertEquals($options['default'], $value[$name]['default']);
        }
    }

    /**
     * Verifies that features are set as expected
     * Available features are: all, get, save, delete
     *
     * @param string $class Full path to the class
     * @param array $features
     */
    protected function verifyFeatures(string $class, array $features)
    {
        $model = new $class($this->config);
        $reflect = new ReflectionClass($model);
        $className = $this->getClassName($class);

        $reflectValue = $reflect->getProperty('features');
        $reflectValue->setAccessible(true);
        $value = $reflectValue->getValue($model);
        $this->assertArrayHasKey('all', $value);
        $this->assertArrayHasKey('get', $value);
        $this->assertArrayHasKey('create', $value);
        $this->assertArrayHasKey('delete', $value);
        $this->assertArrayHasKey('update', $value);
        $this->assertArrayHasKey('order', $value);
        $this->assertArrayHasKey('filter', $value);
        $this->assertEquals('boolean', gettype($value['all']));
        $this->assertEquals('boolean', gettype($value['get']));
        $this->assertEquals('boolean', gettype($value['create']));
        $this->assertEquals('boolean', gettype($value['delete']));
        $this->assertEquals('boolean', gettype($value['update']));
        $this->assertEquals('boolean', gettype($value['order']));
        $this->assertEquals('boolean', gettype($value['filter']));
        $this->assertCount(7, $value);

        foreach (['all', 'get', 'create', 'update', 'delete', 'order', 'filter'] as $feature) {
            $expected = $features[$feature] ? 'true' : 'false';
            $actual = $value[$feature] ? 'true' : 'false';
            $this->assertEquals(
                $features[$feature],
                $value[$feature],
                "Model {$className} Feature {$feature} expected {$expected} got {$actual}"
            );
        }
    }

    /**
     * Verifies that we can load object from passed data (data should be valid, of course)
     *
     * @param string $class Full path to the class
     * @param callable $whatToCheck Verifies fields on loaded model
     */
    protected function verifyInject(string $class, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/GET_{$className}_xx.xml";
        $model = $this->injectData($class, $pathToMock);

        $whatToCheck($model);
    }

    /**
     * Verifies that we can load list of models
     *
     * @param string $class Full path to the class
     * @param callable $whatToCheck Verifies fields on result
     */
    protected function verifyGetAll(string $class, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $mockFile = sprintf('%s/GET_%s.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'GET',
            $class,
            $className,
            $mockFile
        );
        $parameters =[
            'order' => [
                'field' => 'accountID',
                'direction' => 'ASC',
            ],
            'filter' => [
                'name' => ['foo','bar']
            ],
        ];

        $allInstances = $model->all($parameters);
        $this->assertInstanceOf(ModelCollection::class, $allInstances);
        $this->assertObjectHasAttribute('totalResults', $allInstances);
        $this->assertObjectHasAttribute('returnedResults', $allInstances);
        $this->assertObjectHasAttribute('results', $allInstances);

        $whatToCheck($allInstances);
    }

    /**
     * Verifies that we can load list of models by several ids
     *
     * @param string $class Full path to the class
     * @param callable $whatToCheck Verifies fields on result
     */
    protected function verifyGetByIds(string $class, array $ids, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $mockFile = sprintf('%s/GET_%s_xx.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'GET',
            $class,
            $className,
            $mockFile
        );

        $allInstances = $model->getByIds($ids);
        $this->assertInstanceOf(ModelCollection::class, $allInstances);
        $this->assertObjectHasAttribute('totalResults', $allInstances);
        $this->assertObjectHasAttribute('returnedResults', $allInstances);
        $this->assertObjectHasAttribute('results', $allInstances);

        $whatToCheck($allInstances->results);
    }

    /**
     * Verifies that we can load single model
     *
     * @param string $class Full path to the class
     * @param string $id id of the model
     * @param callable $whatToCheck Verifies fields on single model
     */
    protected function verifyGetId(string $class, string $id, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $path = sprintf('%s/Get/%s', $className, $id);
        $mockFile = sprintf('%s/GET_%s_xx.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'GET',
            $class,
            $path,
            $mockFile
        );

        $model->get($id);

        $whatToCheck($model);
    }

    /**
     * Verifies that we can create model
     *
     * @param string $class Full path to the class
     * @param callable $beforeCreate Modifies model before saving
     * @param callable $afterCreate Verifies model after saving
     */
    protected function verifyCreate(string $class, callable $beforeCreate, callable $afterCreate)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/PUT_{$className}_NewAssetAccount_BareMinimum_REQ.xml";
        $path = sprintf('%s/Create', $className);
        $mockFileResponse = sprintf('%s/PUT_%s_NewAssetAccount_BareMinimum_REQ.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'PUT',
            $class,
            $path,
            $mockFileResponse
        );

        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $model->loadResult($data->Account);

        $beforeCreate($model);
        $createdModel = $model->create();
        $afterCreate($createdModel);
    }

    /**
     * Verifies that we can update model
     *
     * @param string $class Full path to the class
     * @param callable $beforeUpdate Modifies model before saving
     * @param callable $afterUpdate Verifies model after saving
     */
    protected function verifyUpdate(string $class, callable $beforeUpdate, callable $afterUpdate)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/POST_{$className}_UpdateAccount_REQ.xml";
        $path = sprintf('%s/Update', $className);
        $mockFileResponse = sprintf('%s/POST_%s_UpdateAccount_REQ.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'PUT',
            $class,
            $path,
            $mockFileResponse
        );

        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        // die(var_dump($data->Account));
        $model->loadResult($data->Account);

        $beforeUpdate($model);
        $updatedModel = $model->update();
        $afterUpdate($updatedModel);
    }

    /**
     * Verifies that we can delete model
     *
     * @param string $class Full path to the class
     * @param int $id Id of the model
     * @param callable $whatToCheck Verifies response
     */
    public function verifyDelete(string $class, int $id, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $path = sprintf('%s/Delete/%s', $className, $id);
        $model = $this->setUpRequestMock('DELETE', $class, $path);

        $model->delete($id);
        // TODO do actual checks
    }

    /**
     * Verifies that exception is thrown when model does not support save()
     *
     * @param string $class Full path to the class
     */
    public function verifySaveException(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Save is not supported");
        $this->expectExceptionCode(ModelException::NO_CREATE_SUPPORT);

        $model = new $class($this->config);
        $model->save();
    }

    /**
     * Verifies that exception is thrown when model does not support delete()
     *
     * @param string $class Full path to the class
     */
    public function verifyDeleteException(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" id 1 Delete is not supported");
        $this->expectExceptionCode(ModelException::NO_DELETE_SUPPORT);

        $model = new $class($this->config);
        $model->delete(1);
    }

    /**
     * Verifies that exception is thrown when model does not support all()
     *
     * @param string $class Full path to the class
     */
    public function verifyAllException(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\"  Get all is not supported");
        $this->expectExceptionCode(ModelException::NO_GET_ALL_SUPPORT);

        $model = new $class($this->config);
        $model->all();
    }

    /**
     * Verifies that exception is thrown when model does not support get()
     *
     * @param string $class Full path to the class
     */
    public function verifyGetException(string $class)
    {
        $className = $this->getClassName($class);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("Model \"{$className}\" id 1 Get single is not supported");

        $model = new $class($this->config);
        $model->get(1);
    }

    /**
     * Verifies that ValidationException for integer out of range is thrown
     * @param string $class Full path to the class
     * @param string $field integer field on class
     * @param int $min min value for field
     * @param int $max max value for field
     * @param int $value value what we are trying to set for field
     */
    public function verifyBadIntegerRangeException(string $class, string $field, int $min, int $max, int $value)
    {
        $className = $this->getClassName($class);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Validation error value %s out of min(%s) max(%s) Integer value is out of range',
                $value,
                $min,
                $max
            )
        );
        $this->expectExceptionCode(ValidationException::INTEGER_OUT_OF_RANGE);

        $model = new $class($this->config);

        $model->{$field} = $value;
    }

    /**
     * Verifies that ValidationException for string with incorrect length is thrown
     * @param string $class Full path to the class
     * @param string $field string field on class
     * @param int $min min length for field
     * @param int $max max length for field
     * @param int $value value what we are trying to set for field
     */
    public function verifyBadStringLengthException(string $class, string $field, int $min, int $max, string $value)
    {
        $className = $this->getClassName($class);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Validation error value This string is too long! out of min(%s) max(%s) String length is out of range',
                $min,
                $max
            )
        );
        $this->expectExceptionCode(ValidationException::STRING_LENGTH_OUT_OF_RANGE);

        $model = new $class($this->config);

        $model->{$field} = $value;
    }

    public function verifyRequestWithAuth(string $class, string $method)
    {
        $className = $this->getClassName($class);

        $config = [
          'key' => 'key',
          'endpoint' => '//api.xero.com/api.xro/2.0',
        ];

        // Creates a partially mock of RequestHandler with mocked `handleRequest` method
        $request = \Mockery::mock(
            'DarrynTen\Xero\Request\RequestHandler[handleRequest]',
            [
                $config,
            ]
        );

        $request->shouldReceive('handleRequest')
            ->once()
            ->with(
                'POST',
                sprintf('//api.xero.com/api.xro/2.0/%s/%s', $className, $method),
                [
                    'headers' => [
                        'Authorization' => 'Basic xx',
                    ],
                    'query' => [
                        'apikey' => 'key'
                    ]
                ],
                []
            )
            ->andReturn('OK');

        $this->assertEquals(
            'OK',
            $request->request('POST', $className, $method, [])
        );
    }

    /**
     * Generates model with injected request which returns what we want
     *
     * @var string $method GET/POST/DELETE/etc.
     * @var string $path part of url
     * @var string $mockFileResponse part to the mock file with response (if it is required)
     * @var string $mockFileRequest part to the mock file with request (if it is required)
     * @return BaseModel
     */
    protected function setUpRequestMock(string $method, string $class, string $path, string $mockFileResponse = null, string $mockFileRequest = null)
    {
        $url = sprintf('/2.0/%s?apikey=key', $path);
        $responseData = null;
        if ($mockFileResponse) {
            $responseData = file_get_contents(__DIR__ . '/../../mocks/Accounting/' . $mockFileResponse);
        }
        $requestData = null;
        if ($mockFileRequest) {
            $requestData = simplexml_load_file(__DIR__ . '/../../mocks/Accounting/' . $mockFileRequest);
        }
        $this->http->mock
            ->when()
            ->methodIs($method)
            ->pathIs($url)
            ->then()
            ->body($responseData)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        $localClient = new Client();
        $localResult = $localClient->request(
            $method,
            '//localhost:8082' . $url,
            []
        );

        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andReturn($localResult);

        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $model = new $class($this->config);

        $modelReflection = new ReflectionClass($model);
        $reflectedRequest = $modelReflection->getProperty('request');
        $reflectedRequest->setAccessible(true);
        $reflectedRequest->setValue($model, $request);

        return $model;
    }

    /**
     * Used to load initial data from file to Account model
     *
     * @param string $class
     * @param string $path
     *
     * @return mixed
     */
    protected function injectData(string $class, string $path)
    {
        $model = new $class($this->config);
        $data = json_decode(json_encode(simplexml_load_file($path)));
        $model->loadResult($data->Account);

        return $model;
    }

    protected function injectPropertyInModel(string $class, string $propertyName, $property)
    {
        $model = new $class($this->config);
        $reflection = new ReflectionClass($model);
        $reflectedFeatures = $reflection->getProperty($propertyName);
        $reflectedFeatures->setAccessible(true);
        $reflectedFeatures->setValue($model, $property);

        return $model;
    }

    /**
     * Verifies that base model validates range
     *
     * @param string $class Full path to the class
     */
    protected function verifyValidateRange(string $class)
    {
        $fields = [
            'integer' => [
                'type' => 'integer',
                'min' => 0,
                'max' => 10,
                'nullable' => true,
                'readonly' => false,
            ]
        ];
        $model = $this->injectPropertyInModel($class, 'fields', $fields);

        $model->integer = 5;
        $this->assertEquals(5, $model->integer);
    }

    /**
     * Verifies that base model validates regexp
     *
     * @param string $class Full path to the class
     *
     * TODO: test the opposite of this
     * (a failing, exception throwing wrong validation)
     */
    protected function verifyValidateRegexp(string $class)
    {
        $fields = [
            'string' => [
                'type' => 'string',
                'regex' => '/^bar$/',
                'nullable' => true,
                'readonly' => false,
            ],
        ];
        $model = $this->injectPropertyInModel($class, 'fields', $fields);

        $model->string = 'bar';
        $this->assertEquals('bar', $model->string);
    }

    /**
     * Verifies that we can create model
     *
     * @param string $class Full path to the class
     */
    protected function verifyNotAllowedPropertyForTypeOnly(string $class)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/{$className}_Invalid_Property_For_Type_Only.xml";

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage(
            "Model \"{$className}\" property bankAccountNumber Property not allowed for this type"
        );
        $this->expectExceptionCode(ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE);

        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $model->loadResult($data->Account);
    }

    /**
     * Verifies that we can create model
     *
     * @param string $class Full path to the class
     */
    protected function verifyAbsentPropertyForType(string $class)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/{$className}_Absent_Property_For_Type.xml";

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage(
            "Model \"{$className}\" property bankAccountNumber Required property for this type missing"
        );
        $this->expectExceptionCode(ModelException::REQUIRED_PROPERTY_MISSING_FOR_TYPE);

        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $model->loadResult($data->Account);
    }

    /**
     * Verifies that we can create model
     *
     * @param string $class Full path to the class
     */
    protected function verifyAbsentPropertyForTypeExcept(string $class)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/{$className}_Invalid_Property_For_Type_Except.xml";

        $model = new $class($this->config);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage(
            "Model \"{$className}\" property description Property not allowed for this type"
        );
        $this->expectExceptionCode(ModelException::NOT_ALLOWED_PROPERTY_FOR_TYPE);

        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $model->loadResult($data->Account);
    }

    /**
     * Verifies that we can create model
     *
     * @param string $class Full path to the class
     */
    protected function verifyNotHaveMinimumPropertiesForCreate(string $class)
    {
        $className = $this->getClassName($class);
        $pathToMock = __DIR__ . "/../../mocks/Accounting/{$className}/{$className}_not_have_minimum_for_create.xml";
        $fields = [
            'code' => [
                'type' => 'string',
                'nullable' => true,
                'readonly' => false,
                'min' => 0,
                'max' => 10,
                'create' => [
                    'exceptType' => 'CURRENT',
                ],
            ],
            'name' => [
                'type' => 'string',
                'nullable' => false,
                'readonly' => false,
                'min' => 0,
                'max' => 150,
                'create' => [
                    'required' => true,
                ],
            ],
            'type' => [
                'type' => 'string',
                'nullable' => false,
                'readonly' => false,
                'required' => true,
                'valid' => 'accountTypes',
                'create' => [
                    'required' => true,
                ],
            ],
        ];

        $model = $this->injectPropertyInModel($class, 'fields', $fields);
        $data = json_decode(json_encode(simplexml_load_file($pathToMock)));
        $model->loadResult($data->Account);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage(
            "Model \"{$className}\" property name Required property missing for create"
        );
        $this->expectExceptionCode(ModelException::REQUIRED_PROPERTY_MISSING_FOR_CREATE);

        $model->create();
    }

    /**
     * @return array
     */
    public function falseFeaturesProvider()
    {
        return [
            [
                [
                    'get' => false,
                    'all' => false,
                    'delete' => false,
                    'create' => false,
                    'update' => false,
                ]
            ]
        ];
    }
}
