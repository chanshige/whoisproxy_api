<?php
declare(strict_types=1);

namespace Chanshige\WhoisProxy\Validation;

use Respect\Validation\Validator as v;

/**
 * Class Dig
 *
 * @package Chanshige\WhoisProxy\Validation
 */
final class Dig implements ValidationInterface
{
    public function rules(): array
    {
        return [
            'domain' => v::notEmpty()->domain(false),
            'q-type' => v::optional(v::alpha()->lowercase()->in(\Chanshige\WhoisProxy\Resource\Dig::$qTypes)),
            'global-server' => v::optional(
                v::oneOf(
                    v::ip(FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE),
                    v::domain(false)
                )
            )
        ];
    }
}
