<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Validation;

use Respect\Validation\Validator as v;

/**
 * Class Whois
 *
 * @package Chanshige\WhoisProxy\Validation
 */
final class Whois implements ValidationInterface
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'domain' => v::notEmpty()->domain(false),
        ];
    }
}
