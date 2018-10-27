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
