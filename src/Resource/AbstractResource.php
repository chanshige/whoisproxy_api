<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource;

/**
 * Class AbstractResource
 *
 * @package Chanshige\WhoisProxy\Resource
 */
abstract class AbstractResource
{
    /**
     * @var array $links
     */
    protected $links = [
        'self' => [
            "href" => ""
        ]
    ];
}
