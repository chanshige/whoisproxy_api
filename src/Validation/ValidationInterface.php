<?php
namespace Chanshige\WhoisProxy\Validation;

/**
 * Interface ValidationInterface
 *
 * @package Chanshige\WhoisProxy\Validation
 */
interface ValidationInterface
{
    /**
     * Set validator rules.
     *
     * @return array
     */
    public function rules(): array;
}
