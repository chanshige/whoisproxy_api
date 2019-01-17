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
use Slim\Http\Response;
use Slim\Http\StatusCode;

$container = $app->getContainer();

$app->add($container->get('middleware:cors'));

/**
 *  Root Access is not allow.
 */
$app->get("/", function (Request $request, Response $response) use ($container) {
    $response = $response->withJson(
        'Bad Request',
        StatusCode::HTTP_BAD_REQUEST
    );

    return $container->get('handler:json')($request, $response);
});

/**
 * APIs
 */
$app->group("/v1", function () use ($app, $container) {
    $app->get(
        "/{type}/{domain}",
        function (Request $request, Response $response, array $args) use ($container) {
            $handler = $container->get('handler:json');
            // validation
            if ($request->getAttribute('has_errors', false)) {
                $response = $response->withJson($request->getAttribute('errors'));
                return $handler($request, $response, StatusCode::HTTP_FORBIDDEN);
            }
            // cache
            if ($request->getAttribute('has_body_cache', false)) {
                return $response->withJson(json_decode((string)$response->getBody()));
            }

            $request = $request->withAttribute('domain', $args['domain']);
            $response = $container->get("resource:{$args['type']}")($request, $response);

            return $handler($request, $response);
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
