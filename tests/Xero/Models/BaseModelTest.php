<?php

namespace DarrynTen\Xero\Tests\Xero\Models;

use DarrynTen\Xero\Request\RequestHandler;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use GuzzleHttp\Client;
use ReflectionClass;

use DarrynTen\Xero\Exception\ModelException;
use DarrynTen\Xero\Models\ModelCollection;
use DarrynTen\Xero\Exception\ValidationException;

abstract class BaseModelTest extends \PHPUnit_Framework_TestCase
{
    use HttpMockTrait;

    protected $config = [
        'username' => 'username',
        'password' => 'password',
        'key' => 'key',
        'endpoint' => '//localhost:8082',
        'version' => '2.0',
        'companyId' => null
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
        $this->expectExceptionCode(10113);

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
        $this->expectExceptionCode(10116);

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
        $this->expectExceptionCode(10111);

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
        $this->expectExceptionCode(10112);

        $obj = new \stdClass;
        $obj->ID = 1;
        $model->loadResult($obj);
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
            'required', 'min', 'max', 'regex', 'valid', 'only', 'except'
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
        $this->assertArrayHasKey('save', $value);
        $this->assertArrayHasKey('delete', $value);
        $this->assertEquals('boolean', gettype($value['all']));
        $this->assertEquals('boolean', gettype($value['get']));
        $this->assertEquals('boolean', gettype($value['save']));
        $this->assertEquals('boolean', gettype($value['delete']));
        $this->assertCount(4, $value);

        foreach (['all', 'get', 'create', 'update', 'delete'] as $feature) {
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

        $model = new $class($this->config);
        $data = json_decode(json_encode(simplexml_load_file(__DIR__ . "/../../mocks/Accounting/{$className}/GET_{$className}_xx.xml")));
        // die(var_dump($data->Account));
        $model->loadResult($data->Account);

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
        $mockFile = sprintf('Accounting/%s/GET_%s.xml', $className, $className);
        $model = $this->setUpRequestMock(
            'GET',
            $class,
            $className,
            $mockFile
        );

        $allInstances = $model->all();
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
     * @param ind $id id of the model
     * @param callable $whatToCheck Verifies fields on single model
     */
    protected function verifyGetId(string $class, int $id, callable $whatToCheck)
    {
        $className = $this->getClassName($class);
        $path = sprintf('%s/Get/%s', $className, $id);
        $mockFile = sprintf('%s/GET_%s_xx.json', $className, $className);
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
     * Verifies that we can save model
     *
     * @param string $class Full path to the class
     * @param callable $beforeSave Modifies model before saving
     * @param callable $afterSave Verifies model after saving
     */
    protected function verifySave(string $class, callable $beforeSave, callable $afterSave)
    {
        $className = $this->getClassName($class);
        $path = sprintf('%s/Save', $className);
        $mockFileResponse = sprintf('%s/POST_%s_Save_RESP.json', $className, $className);
        $mockFileRequest = sprintf('%s/POST_%s_Save_REQ.json', $className, $className);
        $model = $this->setUpRequestMock(
            'POST',
            $class,
            $path,
            $mockFileResponse,
            $mockFileRequest
        );

        $data = simplexml_load_file(__DIR__ . "/../../mocks/Accounting/" . $mockFileRequest);
        $model->loadResult($data);

        $beforeSave($model);
        $savedModel = $model->save();
        $afterSave($savedModel);
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
        $this->expectExceptionCode(10103);

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
        $this->expectExceptionCode(10104);

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
        $this->expectExceptionCode(10101);

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
        $this->expectExceptionCode(10001);

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
        $this->expectExceptionCode(10002);

        $model = new $class($this->config);

        $model->{$field} = $value;
    }

    public function verifyRequestWithAuth(string $class, string $method)
    {
        $className = $this->getClassName($class);

        $config = [
          'username' => 'username',
          'password' => 'password',
          'key' => 'key',
          'endpoint' => '//api.xero.com/api.xro/2.0',
          'version' => '2.0',
          'companyId' => null
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
            $responseData = simplexml_load_file(__DIR__ . '/../../mocks/Accounting/' . $mockFileResponse);
        }
        $requestData = [];
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
}
