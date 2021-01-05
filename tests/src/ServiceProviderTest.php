<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\DomainParser;
use Bakame\Laravel\Pdp\Facades\Rules;
use Bakame\Laravel\Pdp\MisconfiguredExtension;
use ErrorException;
use InvalidArgumentException;
use Pdp\ResolvedDomain;
use Pdp\Rules as PdpRules;
use Pdp\TopLevelDomains as PdpTLD;
use TypeError;

class ServiceProviderTest extends TestCase
{
    public function testExpectedServicesArePopulated(): void
    {
        self::assertTrue($this->app->bound('pdp.parser'));
        self::assertInstanceOf(DomainParser::class, $this->app->make('pdp.parser'));

        self::assertTrue($this->app->bound('pdp.rules'));
        self::assertInstanceOf(PdpRules::class, $this->app->make('pdp.rules'));

        self::assertTrue($this->app->bound('pdp.tld'));
        self::assertInstanceOf(PdpTLD::class, $this->app->make('pdp.tld'));
    }

    public function testUsingAnInvalidCacheStore(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->app['config']->set('domain-parser.cache_driver', 'foo');
        self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    }

    // public function testMissingCacheConfigurationKey(): void
    // {
    //     self::expectException(MisconfiguredExtension::class);
    //     $this->app['config']->set('domain-parser.cache_driver', null);
    //     self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    // }

    public function testCacheWithInvalidType(): void
    {
        self::expectException(ErrorException::class);
        $this->app['config']->set('domain-parser.cache_driver', date_create());
        self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    }

    // public function testCacheWithInvalidTypeTTL(): void
    // {
    //     self::expectException(TypeError::class);
    //     $this->app['config']->set('domain-parser.http_client', 'guzzle');
    //     $this->app['config']->set('domain-parser.cache_ttl', []);
    //     self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    // }
}
