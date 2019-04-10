<?php
namespace Chanshige\WhoisProxy\Factory;

/**
 * Interface FactoryInterface
 *
 * @package Chanshige\WhoisProxy\Factory
 */
interface FactoryInterface
{
    /**
     * Return a new instance object.
     *
     * @param string $name
     * @return object
     */
    public function newInstance(string $name): object;
}
