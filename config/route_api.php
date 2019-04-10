<?php
declare(strict_types=1);

/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Slim\App;
use Slim\Http\Request;
use Chanshige\WhoisProxy\Http\Response;

$container = $app->getContainer();
$app->add($container->get('middleware.cors'));

/**
 * Route access.
 */
$app->get("/", $container->get('notFoundHandler'));

/**
 * API
 */
$app->group("/v1", function (App $app) use ($container) {
    $app->get(
        "/{name}/{domain}[/{option}]",
        function (Request $request, Response $response, array $args) {
            $this->logger->info('[transaction]', $request->getAttributes());
            // body cache.
            if ($request->getAttribute('has_body_cache', false)) {
                return $response;
            }

            $resource = $this->get('factory.resource')
                ->newInstance(strtolower($args['name']));

            return $resource($request, $response, $args);
        }
    )->add($container->get('middleware.cache')
    )->add($container->get('middleware.validate')
    )->add($container->get('validation.api.route'));
});
