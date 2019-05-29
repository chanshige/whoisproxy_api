<?php
namespace Chanshige\WhoisProxy\Handler;

use Chanshige\WhoisProxy\Http\Response;
use Slim\Http\Request;
use Slim\Http\StatusCode;

/**
 * Class NotFoundHandler
 *
 * @package Chanshige\WhoisProxy\Handler
 */
final class NotFoundHandler
{
    /**
     * @var int status code
     */
    private $statusCode = StatusCode::HTTP_NOT_FOUND;

    /**
     * @var string api response message.
     */
    private $message = 'Not Found.';

    /**
     * Invoke.
     *
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
            'doc:whois' => [
                "href" => '/whois/{domain}',
                "title" => 'Lookup find out the registered domain holder.'
            ],
            'doc:dig' => [
                "href" => '/dig/{domain}[/{q-type}]',
                "title" => 'domain information groper.'
            ],
            'reference' => [
                "href" => '',
            ]
        ];

        return $response->withHalJson($this->message, $links, $this->statusCode)
            ->withHeader("Content-type", "application/problem+json;charset=utf-8");
    }

    /**
     * @param string $message
     * @return NotFoundHandler
     */
    public function setMessage(string $message): NotFoundHandler
    {
        $this->message = $message;

        return $this;
    }
}
