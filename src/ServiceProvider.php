<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Closure;
use function config_path;
use function dirname;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Pdp\Rules;
use Pdp\TopLevelDomains;

final class ServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__).'/config/domain-parser.php' => config_path('domain-parser'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([RefreshCacheCommand::class]);
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
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/domain-parser.php', 'domain-parser');

        $this->app->singleton('domain-rules', Closure::fromCallable([Adapter::class, 'getRules']));
        $this->app->singleton('domain-toplevel', Closure::fromCallable([Adapter::class, 'getTLDs']));

        $this->app->alias('domain-rules', Rules::class);
        $this->app->alias('domain-toplevel', TopLevelDomains::class);
    }
}
