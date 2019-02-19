<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
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
        $links = [
            'self' => [
                "href" => $request->getUri()->getPath()
            ],
            'reference' => [
                "href" => ''
            ]
        ];

        return $response->withHalJson($exception->getMessage(), $links, $status)
            ->withHeader("Content-type", "application/problem+json;charset=utf-8");
    }
}
