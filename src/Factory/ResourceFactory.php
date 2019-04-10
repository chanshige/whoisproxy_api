<?php
namespace Chanshige\WhoisProxy\Factory;

use Slim\Http\StatusCode;

/**
 * Class ResourceFactory
 *
 * @package Chanshige\WhoisProxy\Factory
 */
final class ResourceFactory implements FactoryInterface, \IteratorAggregate
{
    /** @var array */
    private $map = [];

    /**
     * ResourceFactory constructor.
     *
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritDoc}
     */
    public function newInstance(string $name): object
    {
        if (!$this->exists($name)) {
            throw new \RuntimeException("Oops!! {$name} is undefined.", StatusCode::HTTP_NOT_FOUND);
        }

        return $this->map[$name];
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->map);
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
