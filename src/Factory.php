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
use Pdp\HttpClientException;
use Pdp\Manager;
use Pdp\Rules;
use Pdp\TopLevelDomains;
use Psr\SimpleCache\CacheInterface;
use Throwable;
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
            if ("Return value of Pdp\Manager::refreshRules() must be of the type bool, null returned" === $e->getMessage()) {
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
            if ("Return value of Pdp\Manager::refreshTLDs() must be of the type bool, null returned" === $e->getMessage()) {
                return $manager->getTLDs($url, $ttl);
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

    private static function getCurlClient(array $options): HttpClient
    {
        return new CurlHttpClient($options);
    }

    private static function getGuzzleClient(array $options): HttpClient
    {
        $guzzle = new GuzzleClient($options);

        return new class($guzzle) implements HttpClient {
            private $client;

            public function __construct(GuzzleClient $client)
            {
                $this->client = $client;
            }

            public function getContent(string $url): string
            {
                try {
                    return $this->client->get($url)->getBody()->getContents();
                } catch (Throwable $e) {
                    throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
                }
            }
        };
    }
}
