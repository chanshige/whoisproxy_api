<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;
use Slim\Http\StatusCode;

$container = $app->getContainer();

$app->add($container->get('middleware:cors'));

/**
 *  Root Access is not allow.
 */
$app->get("/", function (Request $request, Response $response) use ($container) {
    return $response->withHalJson(
        'Bad Request',
        ['self' => ["href" => $request->getUri()->getPath()]],
        StatusCode::HTTP_BAD_REQUEST
    );
});

/**
 * APIs
 */
$app->group("/v1", function () use ($app, $container) {
    $app->get(
        "/{type}/{domain}",
        function (Request $request, Response $response, array $args) use ($container) {
            $this->logger->info('route', $request->getAttributes());
            // validation
            if ($request->getAttribute('has_errors', false)) {
                return $response->withHalJson(
                    $request->getAttribute('errors'),
                    ['self' => ["href" => $request->getUri()->getPath()]],
                    StatusCode::HTTP_FORBIDDEN
                );
            }
            // cache
            if ($request->getAttribute('has_body_cache', false)) {
                return $response->withHeader('Content-Type', 'application/hal+json;charset=utf-8');
            }

            $request = $request->withAttribute('domain', $args['domain']);

            return $container->get("resource:{$args['type']}")($request, $response);
        }
    )->add(
        function (Request $request, Response $response, $next) use ($container) {
            if ($request->getAttribute('has_errors')) {
                return $next($request, $response);
            }

            return $container->get('middleware:cache')($request, $response, $next);
        }
    )->add($container->get('validation:route'));
});
