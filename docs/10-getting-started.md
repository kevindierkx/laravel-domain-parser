# Getting Started

This Laravel package eases [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) _(PDP)_ integration in your Laravel application.

PHP Domain Parser is a resource based domain parser implemented in PHP. With it you can easily parse a domain into its component subdomain, registrable domain, second level domain and public suffix parts using the Public Suffix List or IANA Top Level Domain List.

This package doesn't intent to replace or reinvent the API offered by PDP, instead it offers you various entry points to PDP which can be used to validate or process your domain.

## Installation

The package can be installed via composer:

```bash
composer require bakame/laravel-domain-parser
```

*This package implements Laravel's Package Discovery, no further changes are needed to your application configurations. For more information [please refer to the Laravel documentation](https://laravel.com/docs/packages#package-discovery).*

## Version Compatibility

| Laravel | PHP            | Package | PDP  |
| ------- | -------------- | ------- | ---- |
| 9.x     | ^8.0           | >= 1.1  | ^6.0 |
| 10.x    | ^8.1           | >= 1.2  | ^6.2 |
| 11.x    | ^8.2           | >= 1.3  | ^6.2 |

*Only the currently supported PHP versions are listed. Please [refer to previous releases of this package](https://github.com/kevindierkx/laravel-domain-parser/tags) for support for older PHP or Laravel versions.*

## Configuration

In order to edit the default configuration you need to publish the package configuration to your application config directory:

```bash
php artisan vendor:publish --provider="Bakame\Laravel\Pdp\ServiceProvider" --tag=config
```

The configuration file will be published to `config/domain-parser.php` in your application directory. Please refer to the [config file](https://github.com/kevindierkx/laravel-domain-parser/blob/master/config/domain-parser.php) for an overview of the available options.

## Maintenance

### Refresh Cache Command

You can warm and/or update the cache information using the bundled refresh cache command manually.

You can choose to refresh:

- The Public Suffix List

```bash
php artisan domain-parser:refresh --rules
```

- The IANA Root Zone Database

```bash
php artisan domain-parser:refresh --tlds
```

- Both data sets in a single call (default)

```bash
php artisan domain-parser:refresh
```

### Scheduling

It is recommended to schedule the refresh command so you don't have to manually run `domain-parser:refresh` every time you need to update your cache.

The command can be scheduled in Laravel's console kernel, just like any other command.

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
   $schedule->command('domain-parser:refresh')->daily()->at('04:00');
}
```
