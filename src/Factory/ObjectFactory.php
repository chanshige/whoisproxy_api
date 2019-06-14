<?php
namespace Chanshige\WhoisProxy\Factory;

use Slim\Http\StatusCode;

/**
 * Class ObjectFactory
 *
 * @package Chanshige\WhoisProxy\Factory
 */
final class ObjectFactory implements FactoryInterface, \IteratorAggregate
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
        $this->map = new \ArrayIterator($map);
    }

    /**
     * {@inheritDoc}
     */
    public function newInstance(string $name): object
    {
        if (!$this->map->offsetExists($name)) {
            throw new \RuntimeException("Oops!! {$name} is undefined.", StatusCode::HTTP_NOT_FOUND);
        }

        return $this->map[$name];
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->map;
    }
}
