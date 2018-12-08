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

use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as LaraverServiceProvider;
use Pdp\Domain;
use Pdp\Rules;
use Pdp\TopLevelDomains;
use Throwable;
use function config_path;
use function dirname;

final class ServiceProvider extends LaraverServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__).'/config/domain-parser.php' => config_path('domain-parser'),
        ], 'config');

        $isDomain = static function ($domain): bool {
            try {
                new Domain($domain);
                return true;
            } catch (Throwable $e) {
                return false;
            }
        };

        $isKnown = function ($domain): bool {
            return $this->app->make(Rules::class)->resolve($domain)->isKnown();
        };

        $isICANN = function ($domain): bool {
            return $this->app->make(Rules::class)->resolve($domain)->isICANN();
        };

        $isPrivate = function ($domain): bool {
            return $this->app->make(Rules::class)->resolve($domain)->isPrivate();
        };

        $isTLD = function ($domain): bool {
            return $this->app->make(TopLevelDomains::class)->contains($domain);
        };

        $containsTLD = function ($domain): bool {
            try {
                $domain = new Domain($domain);

                return $this->app->make(TopLevelDomains::class)->contains($domain->getLabel(0));
            } catch (Throwable $e) {
                return false;
            }
        };

        Blade::if('domain_name', $isDomain);
        Blade::if('known_domain_name', $isKnown);
        Blade::if('icann_domain_name', $isICANN);
        Blade::if('private_domain_name', $isPrivate);
        Blade::if('toplevel_domain', $isTLD);
        Blade::if('endswith_toplevel_domain', $containsTLD);

        Validator::extend(
            'is_domain_name',
            function (string $attribute, $value, array $params = [], $validator) use ($isDomain) : bool {
                return $isDomain($value);
            },
            'The :attribute field is not a valid domain name.'
        );

        Validator::extend(
            'is_known_domain_name',
            function (string $attribute, $value, array $params = [], $validator) use ($isKnown) : bool {
                return $isKnown($value);
            },
            'The :attribute field is not a known domain name.'
        );

        Validator::extend(
            'is_icann_domain_name',
            function (string $attribute, $value, array $params = [], $validator) use ($isICANN) : bool {
                return $isICANN($value);
            },
            'The :attribute field is not a ICANN domain name.'
        );

        Validator::extend(
            'is_private_domain_name',
            function (string $attribute, $value, array $params = [], $validator) use ($isPrivate) : bool {
                return $isPrivate($value);
            },
            'The :attribute field is not a private domain name.'
        );

        Validator::extend(
            'is_toplevel_domain',
            function (string $attribute, $value, array $params = [], $validator) use ($isTLD) : bool {
                return $isTLD($value);
            },
            'The :attribute field is not a top level domain.'
        );

        Validator::extend(
            'endswith_toplevel_domain',
            function (string $attribute, $value, array $params = [], $validator) use ($containsTLD) : bool {
                return $containsTLD($value);
            },
            'The :attribute field does end with a top level domain.'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/domain-parser.php', 'domain-parser');

        $this->app->singleton('domain-rules', Closure::fromCallable([Factory::class, 'newRules']));
        $this->app->singleton('domain-toplevel', Closure::fromCallable([Factory::class, 'newTopLevelDomains']));

        $this->app->alias('domain-rules', Rules::class);
        $this->app->alias('domain-toplevel', TopLevelDomains::class);
    }
}
