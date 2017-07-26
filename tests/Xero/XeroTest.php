<?php

namespace DarrynTen\Xero\Tests\Xero;

use DarrynTen\Xero\Xero;
use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\ApiException;
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
        'username' => 'xxx',
        'password' => 'xxx',
        'key' => 'xxx',
        'applicationType' => 'public',
    ];

    public function setUp()
    {
        $this->xero = new Xero($this->config);

        $this->assertEquals($this->xero->config->version, '1.1.2');
        $this->assertEquals($this->xero->config->endpoint, '//api.xero.com/api.xro');
        $this->assertEquals($this->xero->config->cache, true);
        $this->assertEquals($this->xero->config->rateLimit, 5000);
        $this->assertEquals($this->xero->config->rateLimitPeriod, 86400);
        $this->assertEquals($this->xero->config->retries, 3);
        $this->assertEquals($this->xero->config->maxPostSize, 35000);
        $this->assertEquals($this->xero->config->maxFileSize, 100000);

        $expected = [
            'key' => 'xxx',
            'username' => 'xxx',
            'password' => 'xxx',
            'endpoint' => '//accounting.xeroone.co.za',
            'version' => '1.1.2',
            'companyId' => null
        ];
        $this->assertEquals($this->xero->config->getRequestHandlerConfig(), $expected);
    }

    public function testWithOverrides()
    {
        $this->xero = new Xero([
            'key' => 'xxx',
            'username' => 'xxx',
            'password' => 'xxx',
            'endpoint' => 'xxx',
            'version' => 'xxx',
            'companyId' => 2,
            'cache' => false
        ]);

        $this->assertEquals($this->xero->config->version, 'xxx');
        $this->assertEquals($this->xero->config->endpoint, 'xxx');
        $this->assertEquals($this->xero->config->cache, false);

        $expected = [
            'key' => 'xxx',
            'username' => 'xxx',
            'password' => 'xxx',
            'endpoint' => 'xxx',
            'version' => 'xxx',
            'companyId' => 2
        ];
        $this->assertEquals($this->xero->config->getRequestHandlerConfig(), $expected);
    }

    public function testMissingUsername()
    {
        $this->expectException(ApiException::class);
        $request = new Xero([]);
        $this->assertEquals($request->config->version, '1.1.2');
    }

    public function testMissingPassword()
    {
        $this->expectException(ApiException::class);
        $request = new Xero([
            'username' => 'username'
        ]);
        $this->assertEquals($request->config->version, '1.1.2');
    }

    public function testMissingKey()
    {
        $this->expectException(ApiException::class);
        $request = new Xero([
            'username' => 'username',
            'password' => 'password'
        ]);
        $this->assertEquals($request->config->version, '1.1.2');
    }

    public function testRequestGetterResult()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            $this->xero->getRequest()
        );
    }
}
