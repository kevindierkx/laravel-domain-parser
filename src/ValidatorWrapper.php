<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

final class ValidatorWrapper
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
     * @param mixed                                      $value     evaluate using the callable argument
     *                                                              and Laravel Validator::extend method
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param string                                     $attribute
     * @param array                                      $params
     */
    public function __invoke(string $attribute, $value, array $params = [], $validator): bool
    {
        return ($this->callable)($value);
    }
}
