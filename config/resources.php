<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Container\ContainerInterface;

return function (ContainerInterface $container) {
    $container['resource.whois'] = function () use ($container) {
        return new \Chanshige\WhoisProxy\Resource\Whois($container->get('whois'));
    };

    $container['resource.dig'] = function () {
        return new \Chanshige\WhoisProxy\Resource\Dig();
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

    $container['validation.api.route'] = function () {
        return new \DavidePastore\Slim\Validation\Validation((new \Chanshige\WhoisProxy\Validation\ApiRoute)->rules());
    };
};
