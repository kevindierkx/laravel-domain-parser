<?php

/**
 * Laravel Domain Parser Package (https://github.com/bakame-php/laravel-domain-parser).
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
            'is_tld success' => [
                'input' => ['tld' => 'uk'],
                'constraints' => ['tld' => 'is_tld'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_tld fails' => [
                'input' => ['tld' => 'bbc.co.uk'],
                'constraints' => ['tld' => 'is_tld'],
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
            'is_known_suffix success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_known_suffix'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_known_suffix fails' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'is_known_suffix'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_icann_suffix success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_icann_suffix'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_icann_suffix fails' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_icann_suffix'],
                'method' => 'fails',
                'expected' => true,
            ],
            'is_private_suffix success' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_private_suffix'],
                'method' => 'fails',
                'expected' => false,
            ],
            'is_private_suffix fails' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_private_suffix'],
                'method' => 'fails',
                'expected' => true,
            ],
            'contains_tld success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'contains_tld'],
                'method' => 'fails',
                'expected' => false,
            ],
            'contains_tld fails (1)' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'contains_tld'],
                'method' => 'fails',
                'expected' => true,
            ],
            'contains_tld fails (2)' => [
                'input' => ['domain' => '％００.com'],
                'constraints' => ['domain' => 'contains_tld'],
                'method' => 'fails',
                'expected' => true,
            ],
        ];
    }
}
