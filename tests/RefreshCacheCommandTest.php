<?php

namespace BakameTest\Laravel\Pdp;

use Artisan;
use Psr\SimpleCache\CacheInterface;

final class RefreshCacheCommandTest extends TestCase
{
    public function testRefreshRules(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh', ['--rules' => true]));
    }

    public function testRefreshTLDs(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh', ['--tlds' => true]));
    }

    public function testRefreshAll(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh'));
    }

    public function testMissingCache(): void
    {
        $this->app['config']->set('domain-parser.cache_client', null);
        self::assertSame(1, Artisan::call('domain-parser:refresh'));
    }

    public function testWithRefreshError(): void
    {
        $cachePool = new class() implements CacheInterface {
            /**
             * {@inheritdoc}
             */
            public function get($key, $default = null)
            {
                return null;
            }

            /**
             * {@inheritdoc}
             */
            public function set($key, $value, $ttl = null)
            {
                return false;
            }

            /**
             * {@inheritdoc}
             */
            public function delete($key)
            {
                return true;
            }

            /**
             * {@inheritdoc}
             */
            public function clear()
            {
                return true;
            }

            /**
             * {@inheritdoc}
             */
            public function getMultiple($keys, $default = null)
            {
                return [];
            }

            /**
             * {@inheritdoc}
             */
            public function setMultiple($values, $ttl = null)
            {
                return true;
            }

            /**
             * {@inheritdoc}
             */
            public function deleteMultiple($keys)
            {
                return true;
            }

            /**
             * {@inheritdoc}
             */
            public function has($key)
            {
                return true;
            }
        };

        $this->app['config']->set('domain-parser.cache_client', $cachePool);
        self::assertSame(1, Artisan::call('domain-parser:refresh', ['--rules' => true]));
        self::assertSame(1, Artisan::call('domain-parser:refresh', ['--tlds' => true]));
    }
}
