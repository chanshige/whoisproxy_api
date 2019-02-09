<?php
namespace Chanshige\WhoisProxy\Resource;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\WhoisInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class Whois
 *
 * @package Chanshige\WhoisProxy\Resource
 */
final class Whois
{
    /** @var WhoisInterface */
    private $whois;

    /**
     * Whois constructor.
     *
     * @param WhoisInterface $whois
     */
    public function __construct(WhoisInterface $whois)
    {
        $this->whois = $whois;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $this->whois->query($request->getAttribute('domain'), '');

            return $response->withJson(
                $this->whois->results(),
                StatusCode::HTTP_OK,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (InvalidQueryException $e) {
            return $response->withJson(
                $e->getMessage(),
                StatusCode::HTTP_FORBIDDEN,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }
}
