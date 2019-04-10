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
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $this->whois->query($args['domain'], '');

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
