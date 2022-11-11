# Laravel Domain Parser

[![Latest Version](https://img.shields.io/github/tag/kevindierkx/laravel-domain-parser.svg?style=flat-square)](https://github.com/kevindierkx/laravel-domain-parser/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/workflow/status/kevindierkx/laravel-domain-parser/CI-CD/master?style=flat-square)](https://github.com/kevindierkx/laravel-domain-parser/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/kevindierkx/laravel-domain-parser?style=flat-square&token=JBWSCLFCPW)](https://codecov.io/gh/kevindierkx/laravel-domain-parser)

This Laravel package eases [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) _(PDP)_ integration in your Laravel application.

PHP Domain Parser is a resource based domain parser implemented in PHP. With it you can easily parse a domain into its component subdomain, registrable domain, second level domain and public suffix parts using the Public Suffix List or IANA Top Level Domain List.

This package doesn't intent to replace or reinvent the API offered by PDP, instead it offers you various entry points to PDP which can be used to validate or process your domain.

## Documentation

You will find full documentation on the dedicated [documentation](https://distortedfusion.com/docs/kevindierkx/laravel-domain-parser) site.

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
