<?php

namespace BakameTest\Laravel\Pdp;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\Artisan;
use ReflectionClass;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Bakame\Laravel\Pdp\ServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Rules' => \Bakame\Laravel\Pdp\Facades\Rules::class,
            'TopLevelDomains' => \Bakame\Laravel\Pdp\Facades\TopLevelDomains::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('view.paths', [__DIR__.'/../resources/views']);
    }

    /**
     * Render a view with the provided data.
     *
     * @param string $viewName
     * @param array  $withParameters
     *
     * @return array|string
     */
    public function renderView(string $viewName, array $withParameters = [])
    {
        return app(ViewFactory::class)->make($viewName, $withParameters)->render();
    }

    /**
     * Call a protected or private method on an object.
     *
     * @param string $abstract
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function callMethodOnBinding(string $abstract, string $method, array $args)
    {
        $obj = app($abstract);

        $class = new ReflectionClass($obj);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }
}
