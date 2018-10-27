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

/**
 *  Root Access is not allow.
 */
$app->get("/", function (Request $request, Response $response) use ($container) {
    return $response->withJson(['Bad Request'], StatusCode::HTTP_BAD_REQUEST, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
});
