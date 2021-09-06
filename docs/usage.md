# Usage

This package provides some useful Laravel implementations in the form of validation rules, Blade directives and Blade conditionals. Additionally it provides a bridge with the [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) base package, please refer to the [base documentation](https://github.com/jeremykendall/php-domain-parser#documentation) for an overview of all functionality.

## Facades

- `DomainParser` is a Laravel Facade for the `\Bakame\Laravel\Pdp\DomainParser` instance and contains helper methods for interacting with the [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser) base package.

- `Rules` is a Laravel Facade for the `\Pdp\PublicSuffixList` instance, also available as `pdp.rules` from the IOC container.

- `TopLevelDomains` is a Laravel Facade for the `\Pdp\TopLevelDomainsList` instance, also available as `pdp.tld` from the IOC container.

*Please note:* By default all facades will be registered during package discovery. In the following examples we will use these facades directly.

## Validation rules

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

## Blade if statement directives

| If statement     | Description |
| ---------------- | ----------- |
| `domain_name`    | Tells whether the submitted value represents a Domain Name |
| `tld`            | Tells whether the submitted value is a TLD |
| `contains_tld`   | Tells whether the submitted value is a Domain Name with a known TLD |
| `known_suffix`   | Tells whether the submitted value is a Domain Name with a Known suffix |
| `icann_suffix`   | Tells whether the submitted value is a Domain Name with an ICANN suffix |
| `private_suffix` | Tells whether the submitted value is a Domain Name with a Private suffix |

```php
@contains_tld('example.localhost')
OK
@else
KO
@endcontains_tld
{{-- KO --}}
```

## Blade directives

| directive           | Description |
| ------------------- | ----------- |
| `domain_to_unicode` | Converts the hostname into its Unicode representation |
| `domain_to_ascii`   | Converts the hostname into its Ascii representation |

```php
@domain_to_unicode('www.xn--85x722f.xn--55qx5d.cn') {{-- www.食狮.公司.cn --}}
@domain_to_ascii('www.食狮.公司.cn') {{-- www.xn--85x722f.xn--55qx5d.cn --}}
```
