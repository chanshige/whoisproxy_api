<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Http;

use Slim\Http\Body;
use Slim\Http\StatusCode;

/**
 * Class Response (Slim response override.)
 *
 * @package Chanshige\WhoisProxy\Http
 */
class Response extends \Slim\Http\Response
{
    /**
     * Json.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Json
     * response to the client.
     *
     * @param  mixed $data   The data
     * @param  array $links  The links
     * @param  int   $status The HTTP status code.
     * @throws \RuntimeException
     * @return static
     */
    public function withHalJson($data, $links = [], $status = null)
    {
        $response = $this->withBody(new Body(fopen('php://temp', 'r+')))
            ->withHeader('Content-Type', 'application/hal+json;charset=utf-8');
        $response->status = $status ?? $response->getStatusCode();

        $data = [
            "code" => $response->status,
            "state" => ($response->status === StatusCode::HTTP_OK ? "success" : "fail"),
            "_links" => $links,
            "results" => $data
        ];
        $response->body->write($json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        return $response;
    }
}
