<?php
/*
 * This file is part of the WhoisProxy package.
 *
 * (c) Shigeki Tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container = $app->getContainer();

$container['resource:whois'] = function () use ($container) {
    return new \Chanshige\WhoisProxy\Resource\Whois($container->get('whois'));
};

$container['resource:dig'] = function () {
    return new \Chanshige\WhoisProxy\Resource\Dig();
};
