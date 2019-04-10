<?php
declare(strict_types=1);

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
     * @return void
     */
    public function __construct(string $origin)
    {
        $this->allowOrigin = $origin;
    }

    /**
     * {@inheritDoc}
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
