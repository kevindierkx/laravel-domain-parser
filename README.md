# Laravel Domain Parser

[![Latest Version](https://img.shields.io/github/tag/kevindierkx/laravel-domain-parser.svg?style=flat-square)](https://github.com/kevindierkx/laravel-domain-parser/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/workflow/status/kevindierkx/laravel-domain-parser/CI-CD/master?style=flat-square)](https://github.com/kevindierkx/laravel-domain-parser/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/kevindierkx/laravel-domain-parser?style=flat-square&token=JBWSCLFCPW)](https://codecov.io/gh/kevindierkx/laravel-domain-parser)

A Laravel package to ease [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) _(PDP)_ integration in your Laravel application.

## Version Compatibility

| Laravel | PHP            | Package | PDP  | NOTES |
| ------- | -------------- | ------- | ---- | ------|
| 5.x     | ^7.1           | >= 0.3  | ^5.4 |       |
| 6.x     | ^7.2           | >= 0.4  | ^5.4 |       |
| 7.x     | ^7.2           | >= 0.4  | ^5.4 |       |
| 8.x     | ^7.2 \|\| ^8.0 | >= 0.5  | ^5.4 | PHP 8.0 isn't officially supported in this version |
| 8.x     | ^7.4 \|\| ^8.0 | >= 1.0  | ^6.0 |       |

## Installation

Install the package via composer:

```bash
composer require bakame/laravel-domain-parser
```

*This package implements Laravel's Package Discovery, no further changes are needed to your application configs. For more information [please refer to the Laravel documentation](https://laravel.com/docs/packages#package-discovery).*

### Configuration

In order to edit the default configuration you need to publish the package configuration to your application config directory:

```bash
php artisan vendor:publish --provider="Bakame\Laravel\Pdp\ServiceProvider" --tag=config
```

The config file will be published in `config/domain-parser.php`. Please refer to the [config file](./config/domain-parser.php) for an overview of the available options.

## Usage

The package provides some useful Laravel implementations in the form of validation rules, Blade directives and Blade conditionals. Additionally it provides a bridge with the [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) base package, please refer to the [documentation](https://github.com/jeremykendall/php-domain-parser#documentation) for an overview of all functionality.

### Facades

- `DomainParser` is a Laravel Facade for the [`\Bakame\Laravel\Pdp\DomainParser`](./src/Facades/DomainParser.php) instance and contains helper methods for interacting with the [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) base package.

- `Rules` is a Laravel Facade for the [`\Pdp\PublicSuffixList`](https://github.com/jeremykendall/php-domain-parser/blob/master/src/PublicSuffixList.php) instance, also available as `pdp.rules` from the IOC container.

- `TopLevelDomains` is a Laravel Facade for the [`\Pdp\TopLevelDomainsList`](https://github.com/jeremykendall/php-domain-parser/blob/master/src/TopLevelDomainList.php) instance, also available as `pdp.tld` from the IOC container.

*Please note:* By default all facades will be registered during package discovery. In the following examples we will use these facades directly.

### Validation rules

| Rules               | Description |
| ------------------- | ----------- |
| `is_domain_name`    | Tells whether the submitted value represents a Domain Name |
| `is_tld`            | Tells whether the submitted value is a TLD |
| `contains_tld`      | Tells whether the submitted value is a Domain Name with a known TLD |
| `is_known_suffix`   | Tells whether the submitted value is a Domain Name with a known suffix |
| `is_icann_suffix`   | Tells whether the submitted value is a Domain Name with an ICANN suffix |
| `is_private_suffix` | Tells whether the submitted value is a Domain Name with a Private suffix |

```php
$validator = Validator::make($request->all(), [
    'tld' => 'is_tld',
    'domain' => 'is_icann_suffix',
]);
```

### Blade if statement directives

| If statement     | Description |
| ---------------- | ----------- |
| `domain_name`    | Tells whether the submitted value represents a Domain Name |
| `tld`            | Tells whether the submitted value is a TLD |
| `contains_tld`   | Tells whether the submitted value is a Domain Name with a known TLD |
| `known_suffix`   | Tells whether the submitted value is a Domain Name with a Known suffix |
| `icann_suffix`   | Tells whether the submitted value is a Domain Name with an ICANN suffix |
| `private_suffix` | Tells whether the submitted value is a Domain Name with a Private suffix |

```blade
@contains_tld('example.localhost')
OK
@else
KO
@endcontains_tld
{{-- KO --}}
```

### Blade directives
| directive           | Description |
| ------------------- | ----------- |
| `domain_to_unicode` | Converts the hostname into its Unicode representation |
| `domain_to_ascii`   | Converts the hostname into its Ascii representation |

```blade
@domain_to_unicode('www.xn--85x722f.xn--55qx5d.cn') {{-- www.食狮.公司.cn --}}
@domain_to_ascii('www.食狮.公司.cn') {{-- www.xn--85x722f.xn--55qx5d.cn --}}
```

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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

To run the tests, run the following command from the project folder:

``` bash
composer test
```

## Security

If you discover a security vulnerability within this package, please send an e-mail to Kevin Dierkx via kevin@distortedfusion.com. All security vulnerabilities will be promptly addressed.

## Contributing

Contributions are welcome and will be [fully credited](https://github.com/kevindierkx/laravel-domain-parser/graphs/contributors). Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
