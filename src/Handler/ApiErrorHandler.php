<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Handler;

use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
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
        $response = $response->withHalJson(
            $request->getAttribute('errors', $this->message),
            [
                'self' => [
                    "href" => $request->getUri()->getPath()
                ],
                'reference' => [
                    "href" => ''
                ]
            ],
            $this->statusCode
        );

        return $response->withHeader("Content-type", "application/problem+json;charset=utf-8");
    }
}
