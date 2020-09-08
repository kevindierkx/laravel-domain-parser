<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\MisconfiguredExtension;
use Bakame\Laravel\Pdp\RulesFacade as Rules;
use Bakame\Laravel\Pdp\TopLevelDomainsFacade as TopLevelDomains;
use function date_create;
use InvalidArgumentException;
use Pdp\Cache as PdpCache;
use Pdp\CurlHttpClient;
use Pdp\Domain;
use Pdp\Rules as PdpRules;
use Pdp\TopLevelDomains as PdpTLD;
use TypeError;

final class ServiceProviderTest extends TestCase
{
    public function testExpectedServicesArePopulated(): void
    {
        self::assertTrue($this->app->bound('domain-rules'));
        self::assertInstanceOf(PdpRules::class, $this->app->make('domain-rules'));

        self::assertTrue($this->app->bound('domain-toplevel'));
        self::assertInstanceOf(PdpTLD::class, $this->app->make('domain-toplevel'));
    }

    public function testMissingHttpClientConfigurationKey(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.http_client', null);
        Rules::resolve('bbc.co.uk');
    }

    public function testMisconfiguredHttpClientConfiguration(): void
    {
        self::expectException(TypeError::class);
        $this->app['config']->set('domain-parser.http_client', 1.3);
        Rules::resolve('bbc.co.uk');
    }

    public function testUnknownHttpClientConfiguration(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.http_client', 'foobar');
        Rules::resolve('bbc.co.uk');
    }

    public function testHttpClienteWithInvalidType(): void
    {
        self::expectException(TypeError::class);
        $this->app['config']->set('domain-parser.http_client', date_create());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testUsingAnHttpClientObject(): void
    {
        $this->app['config']->set('domain-parser.http_client', new CurlHttpClient());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testUsingGuzzleClientObject(): void
    {
        $this->app['config']->set('domain-parser.cache_client', 'array');
        $this->app['config']->set('domain-parser.http_client', 'guzzle');
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
        self::assertTrue(TopLevelDomains::contains('uk'));
    }

    public function testUsingACacheObject(): void
    {
        $this->app['config']->set('domain-parser.cache_client', new PdpCache());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testUsingAnInvalidCacheStore(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->app['config']->set('domain-parser.cache_client', 'foobar');
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testMissingCacheConfigurationKey(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.cache_client', null);
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testCacheWithInvalidType(): void
    {
        self::expectException(TypeError::class);
        $this->app['config']->set('domain-parser.cache_client', date_create());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testCacheWithInvalidTypeTTL(): void
    {
        self::expectException(TypeError::class);
        $this->app['config']->set('domain-parser.http_client', 'guzzle');
        $this->app['config']->set('domain-parser.cache_ttl', []);
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }
}
