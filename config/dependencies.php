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

$container['notAllowedHandler'] = function () {
    return new \Chanshige\WhoisProxy\Handler\NotAllowedHandler();
};

$container['errorHandler'] = function () {
    return new \Chanshige\WhoisProxy\Handler\BadRequestHandler();
};

$container['phpErrorHandler'] = function () {
    return new \Chanshige\WhoisProxy\Handler\ApiErrorHandler();
};

$container['notFoundHandler'] = function () {
    return new \Chanshige\WhoisProxy\Handler\ApiErrorHandler();
};

$container['logger'] = function () {
    $rotating = new \Monolog\Handler\RotatingFileHandler(env('APP_LOG_FILENAME'));
    $rotating->setFormatter(
        new \Monolog\Formatter\LineFormatter(
            env('APP_LOG_FORMAT'),
            null,
            true,
            true
        )
    );

    $logger = new \Monolog\Logger(env('APP_NAME'));
    $logger->pushHandler($rotating);

    return $logger;
};

$container['file_cache'] = function () {
    return new \Symfony\Component\Cache\Simple\FilesystemCache(
        env('CACHE_DIR_NAMESPACE'),
        env('CACHE_LIFETIME'),
        env('CACHE_DIRECTORY')
    );
};

$container['whois'] = function () {
    return new Chanshige\Whois();
};

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
