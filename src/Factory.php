<?php

/**
 * Twig PHP Domain Parser Extension (https://github.com/bakame-php/laravel-domain-parser).
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
use Pdp\Manager;
use Pdp\Rules;
use Pdp\TopLevelDomains;
use Psr\SimpleCache\CacheInterface;
use function config;

final class Factory
{
    private static function getManager(): Manager
    {
        $config = config('domain-parser');
        if (!isset($config['cache_store'])) {
            throw new MisconfiguredExtension(sprintf(
                'the cache store must be one of your Application cache store identifier OR a %s instance.',
                CacheInterface::class
            ));
        }

        $cache = $config['cache_store'];
        if (is_string($cache)) {
            $cache = Cache::store($cache);
        }

        if (!$cache instanceof CacheInterface) {
            throw new MisconfiguredExtension(sprintf(
                'the cache store must be one of your Application cache store identifier OR a %s instance.',
                CacheInterface::class
            ));
        }

        if (isset($config['curl_options']) && !is_array($config['curl_options'])) {
            throw new MisconfiguredExtension(sprintf(
                'The curl options configuration entry must be an array usable by `curl_setopt_array` %s given',
                gettype($config['curl_options'])
            ));
        }

        return new Manager(
            $cache,
            new CurlHttpClient($config['curl_options']),
            $config['cache_ttl'] ?? null
        );
    }

    public static function newRules(): Rules
    {
        $config = config('domain-parser');

        return self::getManager()->getRules(
            $config['url_psl'] ?? Manager::PSL_URL,
            $config['cache_ttl'] ?? null
        );
    }

    public static function newTopLevelDomains(): TopLevelDomains
    {
        $config = config('domain-parser');

        return self::getManager()->getTLDs(
            $config['url_rzd'] ?? Manager::RZD_URL,
            $config['cache_ttl'] ?? null
        );
    }
}
