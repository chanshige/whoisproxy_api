<?php

declare(strict_types=1);

namespace Chanshige\WhoisProxy\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /** @return static */
    public function onGet(string $name = 'BEAR.Sunday')
    {
        $this->body = [
            'greeting' => 'Hello ' . $name,
        ];

        return $this;
    }
}
