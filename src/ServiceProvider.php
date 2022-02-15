<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (! defined('LARAVEL_PDP_PATH')) {
            define('LARAVEL_PDP_PATH', realpath(__DIR__.'/../'));
        }

        $this->mergeConfigFrom(
            LARAVEL_PDP_PATH.'/config/domain-parser.php', 'domain-parser'
        );

        $this->app->singleton('pdp.parser', function ($app) {
            $config = new DomainParserConfig($app->make('config')->get('domain-parser'));

            return new DomainParser($config);
        });

        $this->app->singleton('pdp.rules', function ($app) {
            return $app->make('pdp.parser')->getRules();
        });
        $this->app->singleton('pdp.tld', function ($app) {
            return $app->make('pdp.parser')->getTopLevelDomains();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->offerPublishing();

        if ($this->app->runningInConsole()) {
            $this->commands([Commands\RefreshCacheCommand::class]);
        }

        $is_domain = [Directives::class, 'isDomain'];
        $is_tld = [Directives::class, 'isTLD'];
        $contains_tld = [Directives::class, 'containsTLD'];
        $is_known_suffix = [Directives::class, 'isKnownSuffix'];
        $is_icann_suffix = [Directives::class, 'isICANNSuffix'];
        $is_private_suffix = [Directives::class, 'isPrivateSuffix'];
        $domain_to_unicode = static function ($expression) {
            return '<?php echo '.Directives::class.'::toUnicode('.$expression.'); ?>';
        };
        $domain_to_ascii = static function ($expression) {
            return '<?php echo '.Directives::class.'::toAscii('.$expression.'); ?>';
        };

        Blade::directive('domain_to_unicode', $domain_to_unicode);
        Blade::directive('domain_to_ascii', $domain_to_ascii);

        Blade::if('domain_name', Closure::fromCallable($is_domain));
        Blade::if('tld', Closure::fromCallable($is_tld));
        Blade::if('contains_tld', Closure::fromCallable($contains_tld));
        Blade::if('known_suffix', Closure::fromCallable($is_known_suffix));
        Blade::if('icann_suffix', Closure::fromCallable($is_icann_suffix));
        Blade::if('private_suffix', Closure::fromCallable($is_private_suffix));

        Validator::extend(
            'is_domain_name',
            Closure::fromCallable(new ValidatorWrapper($is_domain)),
            'The :attribute field is not a valid domain name.'
        );
        Validator::extend(
            'is_tld',
            Closure::fromCallable(new ValidatorWrapper($is_tld)),
            'The :attribute field is not a top level domain.'
        );
        Validator::extend(
            'contains_tld',
            Closure::fromCallable(new ValidatorWrapper($contains_tld)),
            'The :attribute field does end with a top level domain.'
        );
        Validator::extend(
            'is_known_suffix',
            Closure::fromCallable(new ValidatorWrapper($is_known_suffix)),
            'The :attribute field is not a domain with an known suffix.'
        );
        Validator::extend(
            'is_icann_suffix',
            Closure::fromCallable(new ValidatorWrapper($is_icann_suffix)),
            'The :attribute field is not a domain with an ICANN suffix.'
        );
        Validator::extend(
            'is_private_suffix',
            Closure::fromCallable(new ValidatorWrapper($is_private_suffix)),
            'The :attribute field is not a domain with a private suffix.'
        );
    }

    /**
     * Define the configuration publishing.
     *
     * @return void
     */
    protected function offerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                LARAVEL_PDP_PATH.'/config/domain-parser.php' => config_path('domain-parser.php'),
            ], 'config');
        }
    }
}
