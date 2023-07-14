<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp\Facades;

use Illuminate\Support\Facades\Facade;
use \Bakame\Laravel\Pdp\DomainParser as BaseDomainParser;

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
