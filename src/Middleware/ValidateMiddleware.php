<?php
namespace Chanshige\WhoisProxy\Middleware;

/**
 * Class ValidateMiddleware
 *
 * @package Chanshige\WhoisProxy\Middleware
 */
final class ValidateMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke($request, $response, $next)
    {
        // error response.
        if ($request->getAttribute('has_errors', false)) {
            throw new \LogicException('Invalid input error.');
        }

        return $next($request, $response);
    }
}
