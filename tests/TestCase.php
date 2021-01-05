<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\Rules;
use Bakame\Laravel\Pdp\Facades\TopLevelDomains;
use Bakame\Laravel\Pdp\ServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
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
            'Rules' => Rules::class,
            'TopLevelDomains' => TopLevelDomains::class,
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
        /** @phpstan-var view-string $viewName */
        return view($viewName)->with($withParameters)->render();
    }
}
