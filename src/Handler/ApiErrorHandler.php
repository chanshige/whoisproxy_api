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
    private $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @var string api response message.
     */
    private $message = 'Internal Server Error. Please try again later.';

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
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
            ->withHeader("Content-type", "application/problem+json;charset=utf-8");
    }
}
