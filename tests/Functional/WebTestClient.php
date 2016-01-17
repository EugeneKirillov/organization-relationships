<?php
namespace Owr\Tests\Functional;

use Owr\App\App;
use Slim\Http;

/**
 * WebTestClient simulates web browser and makes requests to application
 *
 * @package Owr\Tests\Functional
 */
class WebTestClient
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var array
     */
    protected $server;

    /**
     * @var Http\Request
     */
    protected $request;

    /**
     * @var Http\Response
     */
    protected $response;

    /**
     * WebTestClient constructor
     *
     * @param App $app
     * @param array $server
     */
    public function __construct(App $app, array $server)
    {
        $this->app = $app;

        $this->server = array_merge([
            'SCRIPT_NAME' => '/index.php',
        ], $server);
    }

    /**
     * Perform request
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $server
     *
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function request($method, $uri, array $params = [], array $server = [])
    {
        $method = strtoupper($method);
        switch ($method) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                $this->server['slim.input'] = http_build_query($params);
                $query = '';
            break;

            case 'GET':
            default:
                $query = http_build_query($params);
                break;
        }

        $server = array_merge($this->server, $server, [
            'REQUEST_URI'    => $uri,
            'REQUEST_METHOD' => $method,
            'QUERY_STRING'   => $query,
        ]);
        $env = Http\Environment::mock($server);

        $request  = Http\Request::createFromEnvironment($env);
        $response = new Http\Response();
        $response = $this->app->__invoke($request, $response);

        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * Returns response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Returns response headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * Returns response header by name
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader($name)
    {
        return $this->response->getHeader($name);
    }

    /**
     * Returns response body as a string
     *
     * @return string
     */
    public function getResponse()
    {
        return (string) $this->response->getBody();
    }
}
