<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
date_default_timezone_set('Asia/Tokyo');
require __DIR__ . '/vendor/autoload.php';

const APP_DIR = __DIR__ . '/';

/* Load dotenv */
(new \Dotenv\Dotenv(APP_DIR))->load();

/* to Slim */
$settings['settings'] = [
    'displayErrorDetails' => env('DISPLAY_ERROR_DETAILS', false),
    'addContentLengthHeader' => env('ADD_CONTENT_LENGTH_HEADER', false),
    'determineRouteBeforeAppMiddleware' => env('DETERMINE_ROUTE_BEFORE_APP_MIDDLEWARE', true),
];

$app = (new \Chanshige\WhoisProxy\Bootstrap(new \Slim\App))->get();

$apiRoutes = require APP_DIR . 'config/routes/api.php';
$apiRoutes($app);

$app->run();
