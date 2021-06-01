<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Contracts\WhoisInterface;
use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
use Slim\Http\StatusCode;
use function Chanshige\get_tld;

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
            $whois = $this->whois->response();

            return $response->withHalJson(
                [
                    'domain' => $args['domain'],
                    'servername' => $whois->servername(),
                    'tld' => get_tld($args['domain']),
                    'registered' => $whois->isRegistered(),
                    'reserved' => $whois->isReserved(),
                    'client_hold' => $whois->isClientHold(),
                    'detail' => [
                        'registrant' => $whois->registrant(),
                        'admin' => $whois->admin(),
                        'tech' => $whois->tech(),
                        'billing' => $whois->billing(),
                        'status' => $whois->status(),
                        'date' => $whois->dates(),
                        'name_server' => $whois->nameserver(),
                    ],
                    'raw' => $whois->raw(),
                ],
                $this->links,
                StatusCode::HTTP_OK
            );
        } catch (InvalidQueryException $e) {
            return $response->withHalJson($e->getMessage(), $this->links, StatusCode::HTTP_FORBIDDEN);
        }
    }
}
