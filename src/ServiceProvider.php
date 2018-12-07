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

        $isDomain = static function (string $attribute, $value, array $params = [], $validator): bool {
            try {
                new Domain($value);
                return true;
            } catch (Throwable $e) {
                return false;
            }
        };

        $isKnownDomain = function (string $attribute, $value, array $params, $validator): bool {
            return $this->app->make(Rules::class)->resolve($value)->isKnown();
        };

        $isICANNDomain = function (string $attribute, $value, array $params, $validator): bool {
            return $this->app->make(Rules::class)->resolve($value)->isICANN();
        };

        $isPrivateDomain = function (string $attribute, $value, array $params, $validator): bool {
            return $this->app->make(Rules::class)->resolve($value)->isPrivate();
        };

        $isTopLevelDomain = function (string $attribute, $value, array $params, $validator): bool {
            return $this->app->make(TopLevelDomains::class)->contains($value);
        };

        $containsTopLevelDomain = function (string $attribute, $value, array $params, $validator): bool {
            try {
                $domain = new Domain($value);

                return $this->app->make(TopLevelDomains::class)->contains($domain->getLabel(0));
            } catch (Throwable $e) {
                return false;
            }
        };

        Validator::extend('is_domain_name', $isDomain, 'The :attribute field is not a valid domain name.');
        Validator::extend('is_known_domain_name', $isKnownDomain, 'The :attribute field is not a known domain name.');
        Validator::extend('is_icann_domain_name', $isICANNDomain, 'The :attribute field is not a ICANN domain name.');
        Validator::extend('is_private_domain_name', $isPrivateDomain, 'The :attribute field is not a private domain name.');
        Validator::extend('is_toplevel_domain', $isTopLevelDomain, 'The :attribute field is not a top level domain.');
        Validator::extend('endswith_toplevel_domain', $containsTopLevelDomain, 'The :attribute field does end with a top level domain.');
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/domain-parser.php', 'domain-parser');
        $this->app->singleton('domain-rules', Closure::fromCallable([Factory::class, 'newRules']));
        $this->app->alias('domain-rules', Rules::class);
        $this->app->singleton('domain-toplevel', Closure::fromCallable([Factory::class, 'newTopLevelDomains']));
        $this->app->alias('domain-toplevel', TopLevelDomains::class);
    }
}
