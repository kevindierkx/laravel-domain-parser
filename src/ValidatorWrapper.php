<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Contracts\Validation\Validator;

class ValidatorWrapper
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * New instance.
     *
     * @param callable $callable a callable that takes one argument
     *                           and returns true if the argument is valid
     *                           or false otherwise
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param string    $attribute
     * @param mixed     $value
     * @param array     $params
     * @param Validator $validator
     */
    public function __invoke(string $attribute, $value, array $params, Validator $validator): bool
    {
        return ($this->callable)($value);
    }
}
