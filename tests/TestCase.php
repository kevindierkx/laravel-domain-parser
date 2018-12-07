<?php

/**
 * Twig PHP Domain Parser Extension (https://github.com/bakame-php/laravel-domain-parser).
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\RulesFacade;
use Bakame\Laravel\Pdp\ServiceProvider;
use Bakame\Laravel\Pdp\TopLevelDomainsFacade;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    /**
     * @param Application $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Rules' => RulesFacade::class,
            'TopLevelDomains' => TopLevelDomainsFacade::class,
        ];
    }
}
