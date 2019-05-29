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
            $links = [
                'self' => [
                    "href" => $request->getUri()->getPath()
                ],
                'reference' => [
                    "href" => '',
                ]
            ];

            return $response->withHalJson($request->getAttribute('errors', []), $links, 400)
                ->withHeader("Content-type", "application/problem+json;charset=utf-8");
        }

        return $next($request, $response);
    }
}
