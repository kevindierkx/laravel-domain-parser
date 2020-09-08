<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Support\Facades\Facade;
use Pdp\TopLevelDomains;

final class TopLevelDomainsFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return TopLevelDomains::class;
    }
}
