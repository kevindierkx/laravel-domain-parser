<?php

/**
 * Twig PHP Domain Parser Extension (https://github.com/bakame-php/laravel-domain-parser).
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BakameTest\Laravel\Pdp;

use Validator;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validatorProvider
     */
    public function testValidatorEndsWithTopLevelDomain(
        array $input,
        array $contraints,
        string $method,
        bool $expected
    ): void {
        $validator = Validator::make($input, $contraints);
        self::assertSame($expected, $validator->$method());
    }

    public function validatorProvider(): array
    {
        return [
            'is_toplevel_domain success' => [
                'input' => ['tld' => 'uk'],
                'constraints' => ['tld' => 'is_toplevel_domain'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_toplevel_domain fails' => [
                'input' => ['tld' => 'bbc.co.uk'],
                'constraints' => ['tld' => 'is_toplevel_domain'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_domain_name success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_domain_name'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_domain_name fails' => [
                'input' => ['domain' => '％００.com'],
                'constraints' => ['domain' => 'is_domain_name'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_known_domain_name success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_known_domain_name'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_known_domain_name fails' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'is_known_domain_name'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_icann_domain_name success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_icann_domain_name'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_icann_domain_name fails' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_icann_domain_name'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_private_domain_name success' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_private_domain_name'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_private_domain_name fails' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_private_domain_name'],
                'method' => 'fails',
                'expected' => true,
            ],
            'endswith_toplevel_domain success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'endswith_toplevel_domain'],
                'method' => 'fails',
                'expected' => false,
            ],
            'endswith_toplevel_domain fails (1)' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'endswith_toplevel_domain'],
                'method' => 'fails',
                'expected' => true,
            ],
            'endswith_toplevel_domain fails (2)' => [
                'input' => ['domain' => '％００.com'],
                'constraints' => ['domain' => 'endswith_toplevel_domain'],
                'method' => 'fails',
                'expected' => true,
            ],
        ];
    }
}
