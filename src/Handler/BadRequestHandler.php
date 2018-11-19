<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class BadRequestHandler
 *
 * @package Chanshige\WhoisProxy\Handler
 */
final class BadRequestHandler
{
    /**
     * @param Request    $request
     * @param Response   $response
     * @param \Exception $exception
     * @return Response
     */
    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        $status = $exception->getCode() ?: StatusCode::HTTP_BAD_REQUEST;

        $data = [
            "method" => $request->getMethod(),
            "code" => $status,
            "state" => "fail",
            "message" => $exception->getMessage()
        ];

        return $response->withStatus($status)
            ->withHeader("Content-type", "application/problem+json;charset=utf-8")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
