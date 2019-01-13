<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class ApiErrorHandler
 *
 * @package Chanshige\WhoisProxy\Handler
 */
final class ApiErrorHandler
{
    /**
     * @var int status code
     */
    private $statusCode = StatusCode::HTTP_FORBIDDEN;

    /**
     * @var string api response message.
     */
    private $message = 'Forbidden';

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $data = [
            "method" => $request->getMethod(),
            "code" => $this->statusCode,
            "state" => "fail",
            "message" => $this->message,
        ];

        return $response->withStatus($this->statusCode)
            ->withHeader("Content-type", "application/problem+json;charset=utf-8")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
