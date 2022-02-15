# Installation

The package can be installed via composer:

```bash
composer require bakame/laravel-domain-parser
```

*This package implements Laravel's Package Discovery, no further changes are needed to your application configurations. For more information [please refer to the Laravel documentation](https://laravel.com/docs/packages#package-discovery).*

## Version Compatibility

| Laravel | PHP            | Package | PDP  | NOTES |
| ------- | -------------- | ------- | ---- | ------|
| 5.x     | ^7.1           | >= 0.3  | ^5.4 |       |
| 6.x     | ^7.2           | >= 0.4  | ^5.4 |       |
| 7.x     | ^7.2           | >= 0.4  | ^5.4 |       |
| 8.x     | ^7.2 \|\| ^8.0 | >= 0.5  | ^5.4 | PHP 8.0 isn't officially supported in this version |
| 8.x     | ^7.4 \|\| ^8.0 | >= 1.0  | ^6.0 |       |
| 9.x     | ^8.0           | >= 1.1  | ^6.0 |       |

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
