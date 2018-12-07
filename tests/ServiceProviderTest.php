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

use Bakame\Laravel\Pdp\MisconfiguredExtension;
use InvalidArgumentException;
use Pdp\Cache as PdpCache;
use Pdp\Domain;
use Rules;
use function date_create;

final class ServiceProviderTest extends TestCase
{
    public function testMisconfiguredCurlOptions(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.cache_store', 'array');
        $this->app['config']->set('domain-parser.curl_options', 1.3);
        Rules::resolve('bbc.co.uk');
    }

    public function testSwitchingCache(): void
    {
        $this->app['config']->set('domain-parser.cache_store', new PdpCache());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testInvalidCacheStore(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->app['config']->set('domain-parser.cache_store', 'foobar');
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testCacheStoreMissing(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.cache_store', null);
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testCacheStoreWithInvalidType(): void
    {
        self::expectException(MisconfiguredExtension::class);
        $this->app['config']->set('domain-parser.cache_store', date_create());
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }
}
