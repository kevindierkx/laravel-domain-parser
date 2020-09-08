<?php

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

    /**
     * @param string $viewName
     * @param array  $withParameters
     *
     * @return array|string
     */
    public function renderView(string $viewName, array $withParameters = [])
    {
        return view($viewName)->with($withParameters)->render();
    }
}
