<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Chanshige\WhoisProxy\Resource\{Dig, Whois};
use DavidePastore\Slim\Validation\Validation as Validation;

$container = $app->getContainer();

/*
 * Resource.
 */
$container['resource.whois'] = function () use ($container) {
    return new Whois($container->get('whois'));
};

$container['resource.dig'] = function () {
    return new Dig();
};

/*
 * Validation.
 */
$container['validation.route'] = function () {
    return new Validation((new \Chanshige\WhoisProxy\Validation\ApiRoute)->rules());
};

/*
 * Factories.
 */
$container['factory.resource'] = function () use ($container) {
    return new \Chanshige\WhoisProxy\Factory\ResourceFactory(
        [
            'whois' => $container->get('resource.whois'),
            'dig' => $container->get('resource.dig')
        ]
    );
};
