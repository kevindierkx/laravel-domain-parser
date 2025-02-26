<?php

namespace BakameTest\Laravel\Pdp;

use Illuminate\Support\Facades\Validator;

class ValidatorTest extends TestCase
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
        string $errorMessage,
    ): void {
        $validator = Validator::make($input, $contraints);
        self::assertSame($expected, $validator->fails());
        if (true === $expected) {
            self::assertSame($errorMessage, $validator->messages()->first());
        }
    }

    public static function validatorProvider(): array
    {
        return [
            'is_tld success' => [
                ['tld' => 'uk'],
                ['tld' => 'is_tld'],
                false,
                '',
            ],
            'is_tld fails' => [
                ['tld' => 'bbc.co.uk'],
                ['tld' => 'is_tld'],
                true,
                'The tld field is not a top level domain.',
            ],
            'is_domain_name success' => [
                ['domain' => 'bbc.co.uk'],
                ['domain' => 'is_domain_name'],
                false,
                '',
            ],
            'is_domain_name fails' => [
                ['domain' => '％００.com'],
                ['domain' => 'is_domain_name'],
                true,
                'The domain field is not a valid domain name.',
            ],
            'is_known_suffix success' => [
                ['domain' => 'bbc.co.uk'],
                ['domain' => 'is_known_suffix'],
                false,
                '',
            ],
            'is_known_suffix fails' => [
                ['domain' => 'bbc.co.localhost'],
                ['domain' => 'is_known_suffix'],
                true,
                'The domain field is not a domain with an known suffix.',
            ],
            'is_icann_suffix success' => [
                ['domain' => 'bbc.co.uk'],
                ['domain' => 'is_icann_suffix'],
                false,
                '',
            ],
            'is_icann_suffix fails' => [
                ['domain' => 'thephpleague.github.io'],
                ['domain' => 'is_icann_suffix'],
                true,
                'The domain field is not a domain with an ICANN suffix.',
            ],
            'is_private_suffix success' => [
                ['domain' => 'thephpleague.github.io'],
                ['domain' => 'is_private_suffix'],
                false,
                '',
            ],
            'is_private_suffix fails' => [
                ['domain' => 'bbc.co.uk'],
                ['domain' => 'is_private_suffix'],
                true,
                'The domain field is not a domain with a private suffix.',
            ],
            'contains_tld success' => [
                ['domain' => 'bbc.co.uk'],
                ['domain' => 'contains_tld'],
                false,
                '',
            ],
            'contains_tld fails (1)' => [
                ['domain' => 'bbc.co.localhost'],
                ['domain' => 'contains_tld'],
                true,
                'The domain field does end with a top level domain.',
            ],
            'contains_tld fails (2)' => [
                ['domain' => '％００.com'],
                ['domain' => 'contains_tld'],
                true,
                'The domain field does end with a top level domain.',
            ],
        ];
    }
}
