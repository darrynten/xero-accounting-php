<?php
namespace DarrynTen\Xero\Tests\Xero\Config;

use DarrynTen\Xero\Config\ConfigFactory;
use DarrynTen\Xero\Config\PartnerApplicationConfig;
use DarrynTen\Xero\Config\PrivateApplicationConfig;
use DarrynTen\Xero\Config\PublicApplicationConfig;
use DarrynTen\Xero\Exception\ConfigException;
use DarrynTen\Xero\Exception\ExceptionMessages;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigFactory
     */
    private $configFactory;

    /**
     * Creates an instance of testing object
     */
    public function setUp()
    {
        $this->configFactory = new ConfigFactory();
    }

    /**
     * Checks if instance is of right class
     */
    public function testInstanceOf()
    {
        $this->assertEquals(
            ConfigFactory::class,
            get_class($this->configFactory),
            sprintf('Factory must be instance of %s', ConfigFactory::class)
        );
    }

    /**
     * Checks that right Exception was thrown in case of not set applicationType
     */
    public function testGetConfigNoApplicationType()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::MISSING_APPLICATION_TYPE);
        $this->expectExceptionMessage(
            ExceptionMessages::$configErrorMessages[ ConfigException::MISSING_APPLICATION_TYPE ]
        );

        $this->configFactory->getConfig([ ]);
    }

    /**
     * Checks that right Exception was thrown in case of unknown applicationType
     */
    public function testGetConfigUnknownApplicationType()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::UNKNOWN_APPLICATION_TYPE);
        $this->expectExceptionMessage(
            ExceptionMessages::$configErrorMessages[ ConfigException::UNKNOWN_APPLICATION_TYPE ]
        );

        $this->configFactory->getConfig([
            'applicationType' => 'unknown'
        ]);
    }

    /**
     * @dataProvider dataProvider
     * @param $applicationType
     * @param $expectedClass
     */
    public function testGetConfig($applicationType, $expectedClass)
    {
        $config = $this->configFactory->getConfig([
            'applicationType' => $applicationType,
            'applicationName' => 'test config app',
            'callbackUrl' => 'localhost',
            'key' => 'test',
            'secret' => 'test',
        ]);

        $this->assertInstanceOf(
            $expectedClass,
            $config,
            sprintf(
                "Config must be an instance of %s",
                $expectedClass
            )
        );
    }

    /**
     * Provides test with data
     * $array = [
     *   $applicationType,
     *   $expectedClass,
     * ]
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                ConfigFactory::APPLICATION_TYPE_PUBLIC,
                PublicApplicationConfig::class,
            ],
            [
                ConfigFactory::APPLICATION_TYPE_PRIVATE,
                PrivateApplicationConfig::class,
            ],
            [
                ConfigFactory::APPLICATION_TYPE_PARTNER,
                PartnerApplicationConfig::class,
            ],
        ];
    }
}
