<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Chanshige\Handler\Socket;
use Chanshige\Slim\BodyCache\Cache;
use Chanshige\Whois;
use Chanshige\WhoisProxy\Handler\{NotAllowedHandler, BadRequestHandler, ApiErrorHandler};
use Chanshige\WhoisProxy\Http\Response;
use Chanshige\WhoisProxy\Middleware\SimpleCors;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Slim\Http\Headers;
use Slim\Http\StatusCode;

$container = $app->getContainer();

$container['response'] = function () use ($container) {
    $response = new Response(
        StatusCode::HTTP_OK,
        new Headers(['Content-Type' => 'application/hal+json;charset=utf-8'])
    );

    return $response->withProtocolVersion($container->get('settings')['httpVersion']);
};

$container['notAllowedHandler'] = function () {
    return new NotAllowedHandler();
};

$container['errorHandler'] = function () {
    return new BadRequestHandler();
};

$container['phpErrorHandler'] = function () {
    return new ApiErrorHandler();
};

$container['notFoundHandler'] = function () {
    return new ApiErrorHandler();
};

$container['logger'] = function () {
    $rotating = new RotatingFileHandler(env('APP_LOG_FILENAME'));
    $rotating->setFormatter(
        new LineFormatter(
            "[%datetime%] [%level_name%]: %message% %context%" . PHP_EOL,
            null,
            true,
            true
        )
    );

    $logger = new Logger(env('APP_NAME'));
    $logger->pushHandler($rotating);

    return $logger;
};

$container['whois'] = function () {
    return new Whois(new Socket);
};

$container['middleware:cache'] = function () use ($container) {
    return new Cache(
        new FilesystemCache(
            env('CACHE_DIR_NAMESPACE'),
            env('CACHE_LIFETIME'),
            env('CACHE_DIRECTORY')
        )
    );
};

$container['middleware:cors'] = function () {
    return new SimpleCors(env('ALLOW_ORIGIN'));
};
