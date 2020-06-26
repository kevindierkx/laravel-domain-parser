<?php

/**
 * Laravel Domain Parser Package (https://github.com/bakame-php/laravel-domain-parser)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BakameTest\Laravel\Pdp;

use Artisan;
use Bakame\Laravel\Pdp\RulesFacade;
use Bakame\Laravel\Pdp\ServiceProvider;
use Bakame\Laravel\Pdp\TopLevelDomainsFacade;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

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

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
    }

    public function renderView(string $viewName, array $withParameters = []): string
    {
        return view($viewName)->with($withParameters)->render();
    }
}
