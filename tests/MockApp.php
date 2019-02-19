<?php
namespace Chanshige\WhoisProxy;

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;

/**
 * Trait MockApp
 *
 * @package Chanshige\WhoisProxy
 */
trait MockApp
{
    /** @var Request $request */
    protected $request;

    /** @var Response $response */
    protected $response;

    /**
     * Initialize.
     *
     * @param string $method
     * @param string $path
     * @param array  $query
     * @param string $data
     */
    protected function initialize($method = 'GET', $path = '/', $query = [], $data = '')
    {
        $this->request = $this->requestFactory($method, $path, $query);
        $this->response = $this->responseFactory($data);
    }

    /**
     * @return App
     */
    protected function getApp()
    {
        $config = [
            'settings' => [
                'displayErrorDetails' => false,
                'addContentLengthHeader' => false,
            ]
        ];

        return (new Bootstrap(new App(($config))))->get();
    }

    /**
     * Request.
     *
     * @param string $method
     * @param string $path
     * @param array  $query
     * @return Request
     */
    private function requestFactory(string $method, string $path, array $query): Request
    {
        $environment = Environment::mock(
            [
                'SCRIPT_NAME' => '/index.php',
                'METHOD' => $method,
                'REQUEST_URI' => $path,
                'QUERY_STRING' => http_build_query($query)
            ]
        );
        return Request::createFromEnvironment($environment);
    }

    /**
     * Response.
     *
     * @param string $data
     * @return Response
     */
    private function responseFactory(string $data): Response
    {
        $response = new Response();
        if (strlen($data) > 0) {
            $response->write($data);
        }
        return $response;
    }
}
