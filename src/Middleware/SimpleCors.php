<?php
namespace Chanshige\WhoisProxy\Middleware;

use Psr\Http\Message\ResponseInterface;

/**
 * Class SimpleCors
 *
 * @package Chanshige\WhoisProxy\Middleware
 */
final class SimpleCors implements MiddlewareInterface
{
    /** @var string */
    private $allowOrigin;

    /**
     * SimpleCors constructor.
     *
     * @param string $origin
     */
    public function __construct(string $origin)
    {
        $this->allowOrigin = $origin;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->allowOrigin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET');
    }
}
