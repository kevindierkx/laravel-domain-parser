# Changelog

All Notable changes will be documented in this file

## 1.1.0 - 2022-02-15

### Added

- Support for Laravel 9.x

### Fixed

- None

### Removed

- Dropped support for older PHP version below 8.x

## 1.0.0 - 2021-03-30

### Added

- Upgraded to [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) 6.0, please refer to the [upgrading guide](UPGRADING.md) for breaking changes.

### Fixed

- None

### Removed

- Support for PHP 7.3 and below has been dropped.
- Support for custom HTTP clients has been dropped, please refer to the [upgrading guide](UPGRADING.md).
- Various namespace and config changes, please refer to the [upgrading guide](UPGRADING.md).

## 0.5.1 - 2021-01-12

### Added

- None

### Fixed

- Fixed #14: Made sure the config file is suffixed with .php when publishing the config.

### Removed

- None

## 0.5.0 - 2020-12-15

### Added

- Experimental support for Laravel 8.x on both PHP 7.4 and PHP 8.x.

### Fixed

- None

### Removed

- None

## 0.4.0 - 2020-09-08

- Added support for Laravel 6.x and 7.x.

## 0.3.0 - 2018-12-13

- Added Blade directive `domain_to_ascii`
- Added Blade directive `domain_to_unicode`
- Rename `Constraints` to `Directives`
- Bug fix validation error messages.
- Improve `ServiceProvider`

## 0.2.0 - 2018-12-12

- Rename `Factory` to `Adapter`
- Improve patch to Laravel bug [#26674](https://github.com/laravel/framework/issues/26674)
- Improve `RefreshCacheCommand`
- Added missing typehinting where possible

## 0.1.0 - 2018-12-07

- first release
