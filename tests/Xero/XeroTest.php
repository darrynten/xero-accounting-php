<?php

namespace DarrynTen\Xero\Tests\Xero;

use DarrynTen\Xero\Xero;
use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\ConfigException;
use DarrynTen\Xero\Tests\Xero\Helpers\DataHelper;

class XeroTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Xero
     */
    private $xero;

    /**
     * @var array
     */
    private $config = [
        'key' => 'xxx',
        'secret' => 'xxx',
        'applicationType' => 'public',
        'applicationName' => 'test application',
        'callbackUrl' => 'localhost',
    ];

    public function setUp()
    {
        $this->xero = new Xero($this->config);

        // Passed in
        $this->assertEquals($this->xero->config->key, 'xxx');
        $this->assertEquals($this->xero->config->secret, 'xxx');
        $this->assertEquals($this->xero->config->applicationType, 'public');
        $this->assertEquals($this->xero->config->applicationName, 'test application');
        $this->assertEquals($this->xero->config->callbackUrl, 'localhost');

        // Defaults
        $this->assertEquals($this->xero->config->endpoint, '//api.xero.com/api.xro');
        $this->assertEquals($this->xero->config->cache, true);
        $this->assertEquals($this->xero->config->rateLimit, 5000);
        $this->assertEquals($this->xero->config->rateLimitPeriod, 86400);
        $this->assertEquals($this->xero->config->retries, 3);
        $this->assertEquals($this->xero->config->maxPostSize, 35000);
        $this->assertEquals($this->xero->config->maxFileSize, 100000);

        $expected = [
            'key' => 'xxx',
            'endpoint' => '//api.xero.com/api.xro',
        ];
        $this->assertEquals($this->xero->config->getRequestHandlerConfig(), $expected);
    }

    public function testWithOverrides()
    {
        $this->xero = new Xero([
            'key' => 'xxx',
            'secret' => 'xxx',
            'endpoint' => 'xxx',
            'applicationType' => 'private',
            'applicationName' => 'private app name',
            'callbackUrl' => 'localhost',
            'cache' => false,
        ]);

        $this->assertEquals($this->xero->config->endpoint, 'xxx');
        $this->assertEquals($this->xero->config->cache, false);

        $expected = [
            'key' => 'xxx',
            'endpoint' => 'xxx',
        ];
        $this->assertEquals($this->xero->config->getRequestHandlerConfig(), $expected);
    }

    public function testMissingApplicationType()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config error  Missing application type');
        $this->expectExceptionCode(20402);

        $request = new Xero([]);
    }

    public function testMissingName()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config error  Missing application name');
        $this->expectExceptionCode(20405);

        $request = new Xero([
            'applicationType' => 'public'
        ]);
    }

    public function testMissingCallback()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config error  Missing callback url');
        $this->expectExceptionCode(20406);

        $request = new Xero([
            'applicationType' => 'public',
            'applicationName' => 'failing config',
        ]);
    }

    public function testMissingKey()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config error  Missing application key');
        $this->expectExceptionCode(20401);

        $request = new Xero([
            'applicationType' => 'public',
            'applicationName' => 'failing config',
            'callbackUrl' => 'localhost',
        ]);
    }

    public function testRequestGetterResult()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            $this->xero->getRequest()
        );
    }
}
