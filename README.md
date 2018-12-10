Laravel Domain Parser
============

[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-packagist]][link-packagist]
[![Latest Stable Version][ico-release]][link-release]
[![Software License][ico-license]][link-license]

A Laravel package to ease [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) v5.4+ integration in your Laravel application.

Usage
-------

Once installed and configured, in your Laravel application, you will be able to:

use the following facades:

```php
$domain = Rules::resolve('www.ulb.ac.be'); //$domain is a Pdp\Domain object
echo $domain->getContent();            // 'www.ulb.ac.be'
echo $domain->getPublicSuffix();       // 'ac.be'
echo $domain->getRegistrableDomain();  // 'ulb.ac.be'
echo $domain->getSubDomain();          // 'www'
$domain->isResolvable();               // returns true
$domain->isKnown();                    // returns true
$domain->isICANN();                    // returns true
$domain->isPrivate();                  // returns false

TopLevelDomains::contains('localhost'); // return false
```

Access additional validation rules:

```php
$validator = Validator::make($request->all(), [
    'tld' => 'is_tld',
    'domain' => 'is_icann_suffix',
]);
```

Use conditionnal Blade directives:

```blade
@contains_tld('example.localhost')
OK
@else
KO
@endcontains_tld
// display KO
```

System Requirements
-------

You need:

- **PHP >= 7.1.3** but the latest stable version of PHP is recommended
- you need Laravel **5.5** but the latest stable version is recommended

Installation
--------

```bash
$ composer require bakame/laravel-domain-parser
```

The package will automatically register itself.


Configuration
--------

This is the contents of the published config file

```php
return [
    /**
     * Name of the cache to use. If a string is given it will be one of
     * the store listed in the `stores` configuration array in
     * your `cache` configuration file.
     */
    'cache_client' => 'file',
    /**
     * Cache TTL.
     * - Can be a DateInterval object
     * - If a DateTimeInterface object is given, the TTL will represents
     *   the difference between the given object and the current timestamp
     * - If a string is given is must be usable by DateInterval::createFromDateString
     * - If an int is given, it is consireded as the TTL is seconds
     */
    'cache_ttl' => '1 DAY',
    /**
     * Name of the HTTP Client to use. If a string is given it must be of
     * of the following curl, guzzle.
     * Otherwise you can give an instance of a Pdp\HttpClient implementing object.
     */
    'http_client' => 'curl',
    /**
     * HTTP client options (optional).
     * Additionals options to use when instantiating the curl and/or the guzzle client
     * For the curl client the options takes a array usable by curl_setopt_array
     * For the guzzle client the options will be used on instantiation
     * Not use or check if a Pdp\HttpClient implementing object is given to http_client.
     */
    'http_client_options' => [],
    /**
     * External Public Suffix List URL (optional)
     * If not present or equals to `null` the package will default to the official URL
     */
    'url_psl' => 'https://publicsuffix.org/list/public_suffix_list.dat',
    /**
     * External Root Zone Domain URL (optional)
     * If not present or equals to `null` the package will default to the official URL
     */
    'url_rzd' => 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
];
```

Documentation
-------

### Validation rules

| Rules            | Description |
| ---------------- | :----       |
| `is_domain_name` | Tells whether the submitted value represents a Domain Name |
| `is_tld` | Tells whether the submitted value is a TLD |
| `contains_tld` | Tells whether the submitted value is a Domain Name with a known TLD |
| `is_known_suffix` | Tells whether the submitted value is a Domain Name with a known suffix |
| `is_icann_suffix` | Tells whether the submitted value is a Domain Name with an ICANN suffix |
| `is_private_suffix` | Tells whether the submitted value is a Domain Name with a Private suffix |

### Blade if statement directives

| If statement     | Description |
| ---------------- | :----       |
| `domain_name` | Tells whether the submitted value represents a Domain Name |
| `tld` | Tells whether the submitted value is a TLD |
| `contains_tld` | Tells whether the submitted value is a Domain Name with a known TLD |
| `known_suffix` | Tells whether the submitted value is a Domain Name with a Known suffix |
| `icann_suffix` | Tells whether the submitted value is a Domain Name with an ICANN suffix |
| `private_suffix` | Tells whether the submitted value is a Domain Name with a Private suffix |

### Facades

- `Rules` is a Laravel Facade for `Pdp\Rules` loaded using the configuration files settings.
- `TopLevelDomains` is a Laravel Facade for `Pdp\TopLevelDomains` loaded using the configuration files settings.

Maintenance
-------

### Refresh Cache Command

You can warm the cache using the bundled refresh cache command manually. You can choose to refresh

- the Public Suffix List data

```bash
php artisan domain-parser:refresh --rules
```

- the IANA Root Domain informations

```bash
php artisan domain-parser:refresh --tlds
```

- both set of data in a single call (default action)

```bash
php artisan domain-parser:refresh
```

### Scheduling

It is, however, recommended to schedule this command so you don't have to manually run `domain-parser:resfresh` everytime you need to update your cache or directly when a user interact with your application on production.

The command can be scheduled in Laravel's console kernel, just like any other command.

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
   $schedule->command('domain-parser:refresh')->daily()->at('04:00');
}
```

Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

Testing
-------

The library has a :

- a [PHPUnit](https://phpunit.de) test suite
- a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/).
- a code analysis compliance test suite using [PHPStan](https://github.com/phpstan/phpstan).

To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

Security
-------

If you discover any security related issues, please email nyamsprod@gmail.com instead of using the issue tracker.

Credits
-------

- [ignace nyamagana butera](https://github.com/nyamsprod)
- [All Contributors](https://github.com/thephpleague/uri-query-parser/contributors)

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.


[ico-travis]: https://img.shields.io/travis/bakame-php/laravel-domain-parser/master.svg?style=flat-square
[ico-packagist]: https://img.shields.io/packagist/dt/bakame/laravel-domain-parser.svg?style=flat-square
[ico-release]: https://img.shields.io/github/release/bakame-php/laravel-domain-parser.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-travis]: https://travis-ci.org/bakame-php/laravel-domain-parser
[link-packagist]: https://packagist.org/packages/bakame/laravel-domain-parser
[link-release]: https://github.com/bakame-php/laravel-domain-parser/releases
[link-license]: https://github.com/bakame-php/laravel-domain-parser/blob/master/LICENSE