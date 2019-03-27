<?php
namespace Chanshige\WhoisProxy;

/**
 * Class Factory
 *
 * @package Chanshige\WhoisProxy
 */
final class Factory
{
    /** @var array */
    private $map = [];

    /**
     * Factory constructor.
     *
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Return a new instance object.
     *
     * @param string $name
     * @return object
     */
    public function newInstance(string $name)
    {
        if (!$this->exists($name)) {
            throw new \LogicException("Resource name:{$name} is undefined.");
        }

        return $this->map[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    private function exists(string $name): bool
    {
        return isset($this->map[$name]);
    }
}
