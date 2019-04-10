<?php
namespace Chanshige\WhoisProxy\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewareInterface
 *
 * @package Chanshige\WhoisProxy\Middleware
 */
interface MiddlewareInterface
{
    /**
     * Example middleware invokable class
     *
     * @param ServerRequestInterface $request  PSR7 request
     * @param ResponseInterface      $response PSR7 response
     * @param callable               $next     Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next);
}
