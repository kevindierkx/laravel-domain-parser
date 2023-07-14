<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp\Facades;

use Illuminate\Support\Facades\Facade;
use Pdp\Rules as RulesContract;

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
