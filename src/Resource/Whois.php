<?php
declare(strict_types=1);

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
final class Whois extends AbstractResource
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
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->links['self']['href'] = $request->getUri()->getPath();

        try {
            $this->whois->query($args['domain'], '');

            return $response->withHalJson($this->whois->results(), $this->links, StatusCode::HTTP_OK);
        } catch (InvalidQueryException $e) {
            return $response->withHalJson($e->getMessage(), $this->links, StatusCode::HTTP_FORBIDDEN);
        }
    }
}
