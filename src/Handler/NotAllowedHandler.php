<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
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
        $links = [
            'self' => [
                "href" => $request->getUri()->getPath()
            ],
            'reference' => [
                "href" => ''
            ]
        ];

        return $response->withHalJson($this->message, $links, $this->statusCode)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader("Content-type", "application/problem+json;charset=utf-8");
    }
}
