<?php
namespace DarrynTen\Xero\Tests\Xero\Config;

use DarrynTen\Xero\Config\BaseConfig;
use DarrynTen\Xero\Exception\ConfigException;
use DarrynTen\Xero\Exception\ExceptionMessages;
use ReflectionClass;

class BaseConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test key value
     */
    const TEST_KEY = 'testKey';

    /**
     * Test key value
     */
    const TEST_SECRET = 'testSecret';

    /**
     * Test dummy endpoint value
     */
    const TEST_ENDPOINT = 'http://localhost:8082';

    /**
     * @var BaseConfig
     */
    private $configMock;

    /**
     * Creates mock for an abstract class
     */
    public function setUp()
    {
        $this->configMock = $this
            ->getMockBuilder(BaseConfig::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * Checks that constructor works well and getRequestHandlerConfig method returns right values
     */
    public function testGetRequestHandlerConfig()
    {
        $reflectedClass = new ReflectionClass(BaseConfig::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, [
            'applicationType' => 'private',
            'applicationName' => 'base config app',
            'callbackUrl' => 'localhost',
            'key' => static::TEST_KEY,
            'secret' => static::TEST_SECRET,
            'endpoint' => static::TEST_ENDPOINT
        ]);

        $handlerConfig = $this->configMock->getRequestHandlerConfig();

        $this->assertTrue(is_array($handlerConfig), 'Config is not an array');
        $this->assertArrayHasKey('key', $handlerConfig, 'Config does not contain key `key`');
        $this->assertEquals(static::TEST_KEY, $handlerConfig['key'], 'Key is wrong');
        $this->assertArrayHasKey('endpoint', $handlerConfig, 'Config does not contain key `endpoint`');
        $this->assertEquals(static::TEST_ENDPOINT, $handlerConfig['endpoint'], 'Endpoint is wrong');
    }

    /**
     * Checks that constructor init methods throws Exception
     */
    public function testConstructorException()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::MISSING_APPLICATION_TYPE);
        $this->expectExceptionMessage(ExceptionMessages::$configErrorMessages[ConfigException::MISSING_APPLICATION_TYPE]);

        $reflectedClass = new ReflectionClass(BaseConfig::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, [ ]);
    }

    public function testUnknownApplication()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::UNKNOWN_APPLICATION_TYPE);
        $this->expectExceptionMessage('Config error  Unknown application type');
        $reflectedClass = new ReflectionClass(BaseConfig::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, [
            'applicationType' => 'xxx',
            'applicationName' => 'base config app',
            'callbackUrl' => 'localhost',
            'key' => static::TEST_KEY,
            'secret' => static::TEST_SECRET,
            'endpoint' => static::TEST_ENDPOINT
        ]);
    }

    public function testMissingApplicationSecret()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::MISSING_APPLICATION_SECRET);
        $this->expectExceptionMessage('Config error  Non-Private app but secret is missing');
        $reflectedClass = new ReflectionClass(BaseConfig::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, [
            'applicationType' => 'public',
            'applicationName' => 'base config app',
            'callbackUrl' => 'localhost',
            'key' => static::TEST_KEY,
            'endpoint' => static::TEST_ENDPOINT
        ]);
    }
}
