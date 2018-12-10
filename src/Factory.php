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

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;
use Pdp\CurlHttpClient;
use Pdp\HttpClient;
use Pdp\Manager;
use Pdp\Rules;
use Pdp\TopLevelDomains;
use Psr\SimpleCache\CacheInterface;
use TypeError;
use function config;
use function is_string;

final class Factory
{
    private const SUPPORTED_HTTP_CLIENT = [
        'curl' => 'getCurlClient',
        'guzzle' => 'getGuzzleClient',
    ];

    /**
     * Errors for Laravel 5.8- where PSR SimpleCache is buggy.
     */
    private const LARAVEL_CACHE_ERROR = [
        'tlds' => [
            "Return value of Pdp\Manager::refreshTLDs() must be of the type boolean, null returned",
            "Return value of Pdp\Manager::refreshTLDs() must be of the type bool, null returned",
        ],
        'rules' =>[
            "Return value of Pdp\Manager::refreshRules() must be of the type boolean, null returned",
            "Return value of Pdp\Manager::refreshRules() must be of the type bool, null returned",
        ],
    ];

    /**
     * Returns a Rules instance.
     */
    public static function getRules(): Rules
    {
        $config = config('domain-parser');
        $manager = self::getManager($config);
        $url = $config['url_psl'] ?? Manager::PSL_URL;
        $ttl = $config['cache_ttl'] ?? null;

        try {
            return $manager->getRules($url, $ttl);
        } catch (TypeError $e) {
            if (in_array($e->getMessage(), self::LARAVEL_CACHE_ERROR['rules'], true)) {
                return $manager->getRules($url, $ttl);
            }

            throw $e;
        }
    }

    /**
     * Returns a TopLevelDomains instance.
     */
    public static function getTLDs(): TopLevelDomains
    {
        $config = config('domain-parser');
        $manager = self::getManager($config);
        $url = $config['url_rzd'] ?? Manager::RZD_URL;
        $ttl = $config['cache_ttl'] ?? null;

        try {
            return $manager->getTLDs($url, $ttl);
        } catch (TypeError $e) {
            if (in_array($e->getMessage(), self::LARAVEL_CACHE_ERROR['tlds'], true)) {
                return $manager->getTLDs($url, $ttl);
            }

            throw $e;
        }
    }

    /**
     * Refresh the cache.
     */
    public static function refreshRules(): bool
    {
        $config = config('domain-parser');
        $manager = self::getManager($config);
        $url = $config['url_psl'] ?? Manager::PSL_URL;
        $ttl = $config['cache_ttl'] ?? null;

        try {
            return $manager->refreshRules($url, $ttl);
        } catch (TypeError $e) {
            if (in_array($e->getMessage(), self::LARAVEL_CACHE_ERROR['rules'], true)) {
                return true;
            }

            throw $e;
        }
    }

    /**
     * Refresh the cache.
     */
    public static function refreshTLDs(): bool
    {
        $config = config('domain-parser');
        $manager = self::getManager($config);
        $url = $config['url_rzd'] ?? Manager::RZD_URL;
        $ttl = $config['cache_ttl'] ?? null;

        try {
            return $manager->refreshTLDs($url, $ttl);
        } catch (TypeError $e) {
            if (in_array($e->getMessage(), self::LARAVEL_CACHE_ERROR['tlds'], true)) {
                return true;
            }

            throw $e;
        }
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
                'the `http_client` must be a %s instance or one of the following string %s.',
                HttpClient::class,
                implode(', ', array_keys(self::SUPPORTED_HTTP_CLIENT))
            ));
        }

        if (!is_string($config['http_client'])) {
            return $config['http_client'];
        }

        $method = self::SUPPORTED_HTTP_CLIENT[$config['http_client']] ?? null;
        if (null !== $method) {
            $options = $config['http_client_options'] ?? [];

            return self::$method($options);
        }

        throw new MisconfiguredExtension(sprintf(
            'the `http_client` must be a %s instance or one of the following string %s.',
            HttpClient::class,
            implode(', ', array_keys(self::SUPPORTED_HTTP_CLIENT))
        ));
    }

    /**
     * Returns a Curl implementation of the HttpClient.
     */
    private static function getCurlClient(array $options): HttpClient
    {
        return new CurlHttpClient($options);
    }

    /**
     * Returns a Guzzle 6 implementation of the HttpClient.
     */
    private static function getGuzzleClient(array $options): HttpClient
    {
        return new GuzzleHttpClient(new GuzzleClient($options));
    }
}
