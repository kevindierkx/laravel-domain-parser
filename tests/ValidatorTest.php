<?php

namespace BakameTest\Laravel\Pdp;

use Validator;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validatorProvider
     *
     * @param array  $input
     * @param array  $contraints
     * @param bool   $expected
     * @param string $errorMessage
     */
    public function testValidator(
        array $input,
        array $contraints,
        bool $expected,
        string $errorMessage
    ): void {
        $validator = Validator::make($input, $contraints);
        self::assertSame($expected, $validator->fails());
        if (true === $expected) {
            self::assertSame($errorMessage, $validator->messages()->first());
        }
    }

    public function validatorProvider(): array
    {
        return [
            'is_tld success' => [
                'input' => ['tld' => 'uk'],
                'constraints' => ['tld' => 'is_tld'],
                'expected' => false,
                'message' => '',
            ],
            'is_tld fails' => [
                'input' => ['tld' => 'bbc.co.uk'],
                'constraints' => ['tld' => 'is_tld'],
                'expected' => true,
                'message' => 'The tld field is not a top level domain.',
            ],
            'is_domain_name success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_domain_name'],
                'expected' => false,
                'message' => '',
            ],
            'is_domain_name fails' => [
                'input' => ['domain' => '％００.com'],
                'constraints' => ['domain' => 'is_domain_name'],
                'expected' => true,
                'message' => 'The domain field is not a valid domain name.',
            ],
            'is_known_suffix success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_known_suffix'],
                'expected' => false,
                'message' => '',
            ],
            'is_known_suffix fails' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'is_known_suffix'],
                'expected' => true,
                'message' => 'The domain field is not a domain with an known suffix.',
            ],
            'is_icann_suffix success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_icann_suffix'],
                'expected' => false,
                'message' => '',
            ],
            'is_icann_suffix fails' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_icann_suffix'],
                'expected' => true,
                'message' => 'The domain field is not a domain with an ICANN suffix.',
            ],
            'is_private_suffix success' => [
                'input' => ['domain' => 'thephpleague.github.io'],
                'constraints' => ['domain' => 'is_private_suffix'],
                'expected' => false,
                'message' => '',
            ],
            'is_private_suffix fails' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'is_private_suffix'],
                'expected' => true,
                'message' => 'The domain field is not a domain with a private suffix.',
            ],
            'contains_tld success' => [
                'input' => ['domain' => 'bbc.co.uk'],
                'constraints' => ['domain' => 'contains_tld'],
                'expected' => false,
                'message' => '',
            ],
            'contains_tld fails (1)' => [
                'input' => ['domain' => 'bbc.co.localhost'],
                'constraints' => ['domain' => 'contains_tld'],
                'expected' => true,
                'message' => 'The domain field does end with a top level domain.',
            ],
            'contains_tld fails (2)' => [
                'input' => ['domain' => '％００.com'],
                'constraints' => ['domain' => 'contains_tld'],
                'expected' => true,
                'message' => 'The domain field does end with a top level domain.',
            ],
        ];
    }
}
