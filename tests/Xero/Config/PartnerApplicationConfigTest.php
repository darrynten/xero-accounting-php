<?php
namespace DarrynTen\Xero\Tests\Xero\Config;

use DarrynTen\Xero\Config\PartnerApplicationConfig;
use DarrynTen\Xero\Exception\ConfigException;
use DarrynTen\Xero\Exception\ExceptionMessages;

class PartnerApplicationConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test key value
     */
    const TEST_KEY = 'testKey';

    /**
     * Test secret value
     */
    const TEST_SECRET = 'testSecret';

    /**
     * Test dummy endpoint value
     */
    const TEST_ENDPOINT = 'http://localhost:8082';

    /**
     * @var PartnerApplicationConfig
     */
    private $configObject;

    /**
     * Creates an instance of a test object
     */
    public function setUp()
    {
        $this->configObject = new PartnerApplicationConfig([
            // TODO this should probably figure it out by itself
            'applicationType' => 'partner',
            'applicationName' => 'partner app',
            'callbackUrl' => 'localhost',
            'key' => static::TEST_KEY,
            'secret' => static::TEST_SECRET,
            'endpoint' => static::TEST_ENDPOINT
        ]);
    }

    /**
     * Checks that we have an instance of right class
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(
            PartnerApplicationConfig::class,
            $this->configObject,
            sprintf('Config must be an instance of %s', PartnerApplicationConfig::class)
        );
    }

    /**
     * Checks that getRequestHandlerConfig method returns right values
     */
    public function testGetRequestHandlerConfig()
    {
        $handlerConfig = $this->configObject->getRequestHandlerConfig();

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

        (new PartnerApplicationConfig([ ]));
    }
}
