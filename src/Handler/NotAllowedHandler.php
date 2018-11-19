<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class NotAllowedHandler
 *
 * @package Chanshige\WhoisProxy\Handler
 */
final class NotAllowedHandler
{
    /**
     * @var int status code
     */
    private $statusCode = StatusCode::HTTP_METHOD_NOT_ALLOWED;

    /**
     * @var string api response message.
     */
    private $message = 'Method not allowed.';

    /**
     * Invoke.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $methods
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $methods)
    {
        $data = [
            "method" => $request->getMethod(),
            "code" => $this->statusCode,
            "state" => "fail",
            "message" => $this->message
        ];

        return $response->withStatus($this->statusCode)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader("Content-type", "application/problem+json;charset=utf-8")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
