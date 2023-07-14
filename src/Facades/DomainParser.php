<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp\Facades;

use Bakame\Laravel\Pdp\DomainParser as BaseDomainParser;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin BaseDomainParser
 */
class DomainParser extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pdp.parser';
    }
}
