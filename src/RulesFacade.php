<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Support\Facades\Facade;
use Pdp\Rules;

final class RulesFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return Rules::class;
    }
}
