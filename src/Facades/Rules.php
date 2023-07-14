<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp\Facades;
use Pdp\Rules as RulesContract;

use Illuminate\Support\Facades\Facade;

/** @mixin RulesContract */
class Rules extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pdp.rules';
    }
}
