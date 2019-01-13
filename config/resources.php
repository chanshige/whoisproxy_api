<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use DavidePastore\Slim\Validation\Validation as Validation;

$container = $app->getContainer();

$container['middleware:cache'] = function () use ($container) {
    return new Chanshige\Slim\BodyCache\Cache($container->get('file_cache'));
};

$container['middleware:cors'] = function () use ($container) {
    return new \Chanshige\WhoisProxy\Middleware\SimpleCors(env('API_ENDPOINT_URL'));
};

$container['handler:json'] = function () {
    return new \Chanshige\WhoisProxy\Handler\JsonHandler();
};

$container['validation:route'] = function () use ($container) {
    return new Validation((new \Chanshige\WhoisProxy\Validation\ApiRoute())->rules());
};

$container['resource:whois'] = function () use ($container) {
    return new \Chanshige\WhoisProxy\Resource\Whois($container->get('whois'));
};
