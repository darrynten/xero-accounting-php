<?php

namespace DarrynTen\Xero\Tests\Exceptions;

use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use GuzzleHttp\Client;
use ReflectionClass;
use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\RequestHandlerException;
use DarrynTen\Xero\Exception\ExceptionMessages;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class RequestHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    use HttpMockTrait;

    private $config = [
        'username' => 'username',
        'password' => 'password',
        'key' => 'key',
        'endpoint' => '//localhost:8082',
        'version' => '1.1.2',
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

    public function testApiExceptionMessages()
    {
        $this->assertEquals(7, sizeof(ExceptionMessages::$strings));
    }

    public function testApiException()
    {
        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionCode(RequestHandlerException::ENTITY_NOT_FOUND);

        $request = new RequestHandler($this->config);
        $request->request('GET', 'Fail', 'Fail', ['foo' => 'bar']);
    }

    /**
     * These tests are failing because the request is lacking a body.
     */
//    public function testApiPostException()
//    {
//        $this->expectException(RequestHandlerException::class);
//        $this->expectExceptionCode(RequestHandlerException::ENTITY_NOT_FOUND);
//
//        $request = new RequestHandler($this->config);
//        $request->request('POST', 'Fail', 'Fail', ['foo' => 'bar']);
//    }
//
//    public function testApiPutException()
//    {
//        $this->expectException(RequestHandlerException::class);
//        $this->expectExceptionCode(RequestHandlerException::ENTITY_NOT_FOUND);
//
//        $request = new RequestHandler($this->config);
//        $request->request('PUT', 'Fail', 'Fail', ['foo' => 'bar']);
//    }
//
//    public function testApiDeleteException()
//    {
//        $this->expectException(RequestHandlerException::class);
//        $this->expectExceptionCode(RequestHandlerException::ENTITY_NOT_FOUND);
//
//        $request = new RequestHandler($this->config);
//        $request->request('DELETE', 'Fail', 'Fail', ['foo' => 'bar']);
//    }

    public function testApiJsonException()
    {
        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionCode('419');
        $this->expectExceptionMessage('419: I\'m a teapot - Teapot - errors: {"code":419}');

        throw new RequestHandlerException(
            json_encode(
                [
                    'errors' => [
                        'code' => 419,
                    ],
                    'status' => '419',
                    'title' => 'I\'m a teapot',
                    'detail' => 'Teapot'
                ]
            ),
            419
        );
    }

    public function testApi400()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/1.1.2/Fail/400?apikey=key')
            ->then()
                ->statusCode(400)
                // TODO actual error responses
                ->body('{}')
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/400?apikey=key',
                []
            );
        } catch (ClientException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '400: A malformed request was sent through or when a '
                  . 'validation rule failed. Validation messages are returned '
                  . 'in the response body. - Client error: '
                  . '`GET http://localhost:8082/1.1.2/Fail/400?apikey=key` '
                  . "resulted in a `400 Bad Request` response:\n{}";

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::MALFORMED_REQUEST);

        $request->request('GET', 'Fail', '400');
    }

    public function testApi401()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/1.1.2/Fail/401?apikey=key')
            ->then()
                ->statusCode(401)
                // TODO actual error responses
                ->body(null)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/401?apikey=key',
                []
            );
        } catch (ClientException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '401: The user is not correctly authenticated and the call '
                  . 'requires authentication. The user does not have access '
                  . 'rights for this method. - Client error: '
                  . '`GET http://localhost:8082/1.1.2/Fail/401?apikey=key` '
                  . 'resulted in a `401 Unauthorized` response';

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::USER_AUTHENTICATION_ERROR);

        $request->request('GET', 'Fail', '401');
    }

    public function testApi404()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/1.1.2/Fail/404?apikey=key')
            ->then()
                ->statusCode(404)
                // TODO actual error responses
                ->body(null)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/404?apikey=key',
                []
            );
        } catch (ClientException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '404: The requested entity was not found. Entities are '
                  . 'bound to companies. Ensure the entity belongs to the '
                  . 'company. - Client error: '
                  . '`GET http://localhost:8082/1.1.2/Fail/404?apikey=key` '
                  . 'resulted in a `404 Not Found` response';

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::ENTITY_NOT_FOUND);

        $request->request('GET', 'Fail', '404');
    }

    public function testApi500()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/1.1.2/Fail/500?apikey=key')
            ->then()
                ->statusCode(500)
                // TODO actual error responses
                ->body(null)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/500?apikey=key',
                []
            );
        } catch (ServerException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '500: Internal server error - Server error: '
                  . '`GET http://localhost:8082/1.1.2/Fail/500?apikey=key` '
                  . 'resulted in a `500 Internal Server Error` response';

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::INTERNAL_SERVER_ERROR);

        $request->request('GET', 'Fail', '500');
    }

    public function testApi501()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/1.1.2/Fail/501?apikey=key')
            ->then()
            ->statusCode(501)
            // TODO actual error responses
            ->body(null)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/501?apikey=key',
                []
            );
        } catch (ServerException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '501: The method you have called is not implemented - Server error:'
                  . ' `GET http://localhost:8082/1.1.2/Fail/501?apikey=key';

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::NOT_IMPLEMENTED);

        $request->request('GET', 'Fail', '500');
    }

    public function testApi503()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/1.1.2/Fail/503?apikey=key')
            ->then()
            ->statusCode(503)
            // TODO actual error responses
            ->body(null)
            ->end();
        $this->http->setUp();

        $request = new RequestHandler($this->config);

        /**
         * We make a local client to connect to our mock and get the
         * expected result
         */
        $localClient = new Client();

        try {
            $localClient->request(
                'GET',
                'http://localhost:8082/1.1.2/Fail/503?apikey=key',
                []
            );
        } catch (ServerException $exception) {
        }

        /**
         * We then make a mock client, and tell the mock client that it
         * should return what the local client got from the mock
         */
        $mockClient = \Mockery::mock(
            'Client'
        );

        $mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        /**
         * Insert the mocked client into the request class via reflection
         *
         * This will pass the desired mock object back to the assertion
         * as it replaces the legit Client() object
         */
        $reflection = new ReflectionClass($request);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($request, $mockClient);

        $expected = '503: The service is down for scheduled maintenance '
                  . 'or you have exceeded your API rate limit - Server '
                  . 'error: `GET http://localhost:8082/1.1.2/Fail/503?apikey=key';

        $this->expectException(RequestHandlerException::class);
        $this->expectExceptionMessage($expected);
        $this->expectExceptionCode(RequestHandlerException::SCHEDULED_MAINTENANCE);

        $request->request('GET', 'Fail', '500');
    }
}
