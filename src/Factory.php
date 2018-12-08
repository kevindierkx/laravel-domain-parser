<?php

/**
 * Laravel Domain Parser Package (https://github.com/bakame-php/laravel-domain-parser).
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Support\Facades\Cache;
use Pdp\CurlHttpClient;
use Pdp\HttpClient;
use Pdp\Manager;
use Pdp\Rules;
use Pdp\TopLevelDomains;
use Psr\SimpleCache\CacheInterface;
use function config;

final class Factory
{
    /**
     * Returns a Rules instance.
     */
    public static function newRules(): Rules
    {
        $config = config('domain-parser');

        return self::getManager($config)->getRules(
            $config['url_psl'] ?? Manager::PSL_URL,
            $config['cache_ttl'] ?? null
        );
    }

    /**
     * Returns a TopLevelDomains instance.
     */
    public static function newTopLevelDomains(): TopLevelDomains
    {
        $config = config('domain-parser');

        return self::getManager($config)->getTLDs(
            $config['url_rzd'] ?? Manager::RZD_URL,
            $config['cache_ttl'] ?? null
        );
    }

    /**
     * Returns a Manager instance.
     */
    private static function getManager(array $config): Manager
    {
        return new Manager(
            self::getCache($config),
            self::getHttpClient($config),
            $config['cache_ttl'] ?? null
        );
    }

    /**
     * Returns a CacheInterface instance.
     *
     * @throws MisconfiguredExtension if the cache_client index is missing
     */
    private static function getCache(array $config): CacheInterface
    {
        if (!isset($config['cache_client'])) {
            throw new MisconfiguredExtension(sprintf(
                'the cache store must be one of your Application cache store identifier OR a %s instance.',
                CacheInterface::class
            ));
        }

        $cache = $config['cache_client'];
        if (is_string($cache)) {
            return Cache::store($cache);
        }

        return $cache;
    }

    /**
     * Returns a HttpClient instance.
     *
     * @throws MisconfiguredExtension if the http_client index are missing
     */
    private static function getHttpClient(array $config): HttpClient
    {
        if (!isset($config['http_client'])) {
            throw new MisconfiguredExtension(sprintf(
                'the `http_client` must be a %s instance of an array usable by curl_setop_array function.',
                HttpClient::class
            ));
        }

        if (is_array($config['http_client'])) {
            return new CurlHttpClient($config['http_client']);
        }

        return $config['http_client'];
    }
}
