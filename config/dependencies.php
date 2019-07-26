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
use Chanshige\WhoisProxy\Handler\{ApiErrorHandler, BadRequestHandler, NotAllowedHandler, NotFoundHandler};
use Chanshige\WhoisProxy\Http\Response;
use Chanshige\WhoisProxy\Middleware\SimpleCors;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Slim\Http\Headers;
use Slim\Http\StatusCode;
use Slim\HttpCache\CacheProvider;

return function (ContainerInterface $container) {
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
        return new NotFoundHandler();
    };

    $container['cache'] = function () {
        return new CacheProvider();
    };

    $container['logger'] = function () {
        $rotating = new RotatingFileHandler(env('APP_LOG_FILENAME'));
        $rotating->setFormatter(
            new LineFormatter(
                env('APP_LOG_FORMAT') . PHP_EOL,
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

    $container['middleware.cache'] = function () use ($container) {
        return new Cache(
            new Psr16Cache(
                new FilesystemAdapter(
                    env('CACHE_DIR_NAMESPACE'),
                    env('CACHE_LIFETIME'),
                    env('CACHE_DIRECTORY')
                )
            )
        );
    };

    $container['middleware.cors'] = function () {
        return new SimpleCors(env('ALLOW_ORIGIN'));
    };

    $container['middleware.validate'] = function () {
        return new \Chanshige\WhoisProxy\Middleware\ValidateMiddleware();
    };

    $container['middleware.http.cache'] = function () {
        return new \Slim\HttpCache\Cache('public', env('CACHE_LIFETIME'));
    };
};
