<?php
namespace Chanshige\WhoisProxy\Resource;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\WhoisInterface;
use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
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
            // TODO:とりあえず
            if (!is_null($request->getAttribute('option'))) {
                throw new InvalidQueryException('Whois resource is not option param.');
            }

            $this->whois->query($request->getAttribute('domain'), '');

            return $response->withHalJson(
                $this->whois->results(),
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_OK
            );
        } catch (InvalidQueryException $e) {
            return $response->withHalJson(
                $e->getMessage(),
                ['self' => ["href" => $request->getUri()->getPath()]],
                StatusCode::HTTP_FORBIDDEN
            );
        }
    }
}
