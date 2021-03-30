# Laravel Domain Parser Upgrade Guide

Please refer to the [jeremykendall/php-domain-parser upgrading guide](https://github.com/jeremykendall/php-domain-parser/blob/develop/UPGRADING.md) for a complete overview of all internal changes.

## Upgrading to 1.x

In order to stay compatible with the base package, this package dropped all support for PHP versions before and including 7.3. The minimum supported PHP version is now PHP 7.4 and the minimum supported Laravel version is 8.x.

### Backwards compatibility changes

The following changes might directly impact you installation.

#### Namespace and class changes

- The `Adapter` class has been replaced with the `DomainParser` class and is now registered in the Laravel IOC container:

```diff
<?php
- \Bakame\Laravel\Pdp\Adapter::class
+ \Bakame\Laravel\Pdp\DomainParser::class
```

- The `Adapter::getTLDs()` method has been replaced with `DomainParser::getTopLevelDomains()`.

- The `Adapter::refreshRules()` and `Adapter::refreshTLDs()` methods have been removed. Please use the `domain-parser:refresh` artisan command to refresh the cache instead.

- Facade classes are now in their own namespace:

```diff
<?php
+ \Bakame\Laravel\Pdp\Facades\DomainParser::class
```

```diff
<?php
- \Bakame\Laravel\Pdp\RulesFacade::class
+ \Bakame\Laravel\Pdp\Facades\Rules::class
```

```diff
<?php
- \Bakame\Laravel\Pdp\TopLevelDomainsFacade::class
+ \Bakame\Laravel\Pdp\Facades\TopLevelDomains::class
```

- The refresh cache command is now in its own namespace:

```diff
<?php
- \Bakame\Laravel\Pdp\RefreshCacheCommand::class
+ \Bakame\Laravel\Pdp\Commands\RefreshCacheCommand::class
```

#### Custom HTTP Client support removed

Previously you could choose a HTTP client implementing `\Pdp\HttpClient`. This has been removed from the base package without a replacement.

Since Laravel comes with [guzzle/guzzle](https://github.com/guzzle/guzzle) by default we now only support guzzle and removed the `http_client` config option.

#### Config option changes

- The `http_client` option has been removed.

- The `cache_client` option has been renamed to `cache_driver`.

This option refers to a cache driver configured in `config/cache.php`. When set to `null` the default application cache driver is used.

- The `cache_ttl` option now only supports int|null and expects a number in minutes instead of seconds.

- The `http_client_options` option hasn't changed, but now only applies to guzzle client options.

- The `url_psl` option has been renamed to `uri_public_suffix_list`.

- The `url_rzd` option has been renamed to `uri_top_level_domain_list`.
