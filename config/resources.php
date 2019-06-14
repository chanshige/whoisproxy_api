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
    /*
     * Resource.
     */
    $container['resource.whois'] = function () use ($container) {
        return new \Chanshige\WhoisProxy\Resource\Whois($container->get('whois'));
    };

    $container['resource.dig'] = function () {
        return new \Chanshige\WhoisProxy\Resource\Dig();
    };

    $container['factory.resource'] = function () use ($container) {
        return new \Chanshige\WhoisProxy\Factory\ObjectFactory(
            [
                'whois' => $container->get('resource.whois'),
                'dig' => $container->get('resource.dig')
            ]
        );
    };

    /*
     * Validation
     */
    $container['validation.whois'] = function () {
        return new \DavidePastore\Slim\Validation\Validation(
            (new \Chanshige\WhoisProxy\Validation\Whois)->rules()
        );
    };

    $container['validation.dig'] = function () {
        return new \DavidePastore\Slim\Validation\Validation(
            (new \Chanshige\WhoisProxy\Validation\Dig)->rules()
        );
    };

    $container['factory.validation'] = function () use ($container) {
        return new \Chanshige\WhoisProxy\Factory\ObjectFactory(
            [
                'whois' => $container->get('validation.whois'),
                'dig' => $container->get('validation.dig')
            ]
        );
    };
};
