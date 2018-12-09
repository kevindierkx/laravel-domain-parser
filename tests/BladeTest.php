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

final class BladeTest extends TestCase
{
    /**
     * @dataProvider isDomainNameProvider
     */
    public function testIsDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('domain_name', ['domain' => $domain]));
    }

    public function isDomainNameProvider(): iterable
    {
        return [
            'valid domain name' => [
                'domain' => 'bbc.co.uk',
                'expected' => "OK\n",
            ],
            'invalid domain name' => [
                'domain' => '[::1]',
                'expected' => "KO\n",
            ],
        ];
    }

    /**
     * @dataProvider isKnownDomainNameProvider
     */
    public function testIsKnownDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('known_domain_name', ['domain' => $domain]));
    }

    public function isKnownDomainNameProvider(): iterable
    {
        return [
            'valid domain name' => [
                'domain' => 'bbc.co.uk',
                'expected' => "OK\n",
            ],
            'invalid domain name' => [
                'domain' => 'bakame-php.localhost',
                'expected' => "KO\n",
            ],
        ];
    }

    /**
     * @dataProvider isICANNDomainNameProvider
     */
    public function testIsICANNDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('icann_domain_name', ['domain' => $domain]));
    }

    public function isICANNDomainNameProvider(): iterable
    {
        return [
            'valid domain name' => [
                'domain' => 'bbc.co.uk',
                'expected' => "OK\n",
            ],
            'invalid domain name' => [
                'domain' => 'bakame-php.github.io',
                'expected' => "KO\n",
            ],
        ];
    }

    /**
     * @dataProvider isPrivateDomainNameProvider
     */
    public function testIsPrivateDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('private_domain_name', ['domain' => $domain]));
    }

    public function isPrivateDomainNameProvider(): iterable
    {
        return [
            'valid domain name' => [
                'domain' => 'bbc.co.github.io',
                'expected' => "OK\n",
            ],
            'invalid domain name' => [
                'domain' => 'ulb.ac.be',
                'expected' => "KO\n",
            ],
        ];
    }

    /**
     * @dataProvider isTopLevelDomainProvider
     */
    public function testIsTopLevelDomain(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('toplevel_domain', ['domain' => $domain]));
    }

    public function isTopLevelDomainProvider(): iterable
    {
        return [
            'valid top level domain' => [
                'domain' => 'be',
                'expected' => "OK\n",
            ],
            'invalid top level domain' => [
                'domain' => 'localhost',
                'expected' => "KO\n",
            ],
        ];
    }

    /**
     * @dataProvider endsWithTopLevelDomainProvider
     */
    public function testEndsWithTopLevelDomain(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('endswith_toplevel_domain', ['domain' => $domain]));
    }

    public function endsWithTopLevelDomainProvider(): iterable
    {
        return [
            'ends with valid top level domain' => [
                'domain' => 'ulb.ac.be',
                'expected' => "OK\n",
            ],
            'ends with invalid top level domain' => [
                'domain' => 'bbc.co.localhost',
                'expected' => "KO\n",
            ],
            'invalid domain name' => [
                'domain' => '[::1]',
                'expected' => "KO\n",
            ],
        ];
    }
}
