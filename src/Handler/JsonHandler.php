<?php
namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class JsonHandler
 *
 * @package Chanshige\WhoisProxy\Handler
 */
final class JsonHandler
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param int|null $code
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $code = null)
    {
        $status = $code ?? $response->getStatusCode();
        $result = [
            "method" => $request->getMethod(),
            "code" => $status,
            "state" => ($status === StatusCode::HTTP_OK ? "success" : "fail"),
            "results" => json_decode($response->getBody(), true)
        ];

        return $response->withJson($result, $status);
    }
}
