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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

return function (App $app) {
    $c = $app->getContainer();
    $app->add($c->get('middleware.cors'));

    /**
     * Route access.
     */
    $app->get(
        "/",
        $c->get('notFoundHandler')->setMessage('Welcome to a whoisproxy api.')
    );

    /**
     * API resource.
     */
    $app->get(
        "/{name:[a-z]+}/[{domain}[/{q-type}[/{global-server}]]]",
        function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
            $this->logger->info('[transaction]', $request->getAttributes());
            // body cache.
            if ($request->getAttribute('has_body_cache', false)) {
                return $response;
            }

            $resource = $this->get('factory.resource')
                ->newInstance(strtolower($args['name']));

            return $resource($request, $response, $args);
        }
    )->add(
        $c->get('middleware.cache')
    )->add(
        $c->get('middleware.validate')
    )->add(
        function (ServerRequestInterface $request, ResponseInterface $response, $next) {
            /** @var \Slim\Interfaces\RouteInterface $route */
            $route = $request->getAttribute('route');

            $validation = $this->get('factory.validation')
                ->newInstance(strtolower($route->getArgument('name')));

            return $validation($request, $response, $next);
        }
    );
};
