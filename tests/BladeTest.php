<?php

namespace BakameTest\Laravel\Pdp;

final class BladeTest extends TestCase
{
    /**
     * @dataProvider isDomainNameProvider
     *
     * @param string $domain
     * @param string $expected
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
     *
     * @param string $domain
     * @param string $expected
     */
    public function testIsKnownDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('known_suffix', ['domain' => $domain]));
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
     *
     * @param string $domain
     * @param string $expected
     */
    public function testIsICANNDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('icann_suffix', ['domain' => $domain]));
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
     *
     * @param string $domain
     * @param string $expected
     */
    public function testIsPrivateDomainName(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('private_suffix', ['domain' => $domain]));
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
     *
     * @param string $domain
     * @param string $expected
     */
    public function testIsTopLevelDomain(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('tld', ['domain' => $domain]));
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
     *
     * @param string $domain
     * @param string $expected
     */
    public function testEndsWithTopLevelDomain(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('contains_tld', ['domain' => $domain]));
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

    /**
     * @dataProvider domainConversionProvider
     *
     * @param string $domain
     * @param string $expected
     */
    public function testDomainToUnicodeDirective(string $domain, string $expected): void
    {
        self::assertSame($expected, $this->renderView('domain_to_unicode', ['domain' => $domain]));
    }

    public function domainConversionProvider(): iterable
    {
        return [
            'ascii domain' => [
                'domain' => 'ulb.ac.be',
                'expected' => "Domain to Unicode: ulb.ac.be\nDomain to Ascii: ulb.ac.be",
            ],
            'unicode domain in ascii form' => [
                'domain' => 'www.xn--85x722f.xn--55qx5d.cn',
                'expected' => "Domain to Unicode: www.食狮.公司.cn\nDomain to Ascii: www.xn--85x722f.xn--55qx5d.cn",
            ],
            'unicode domain' => [
                'domain' => 'www.食狮.公司.cn',
                'expected' => "Domain to Unicode: www.食狮.公司.cn\nDomain to Ascii: www.xn--85x722f.xn--55qx5d.cn",
            ],
            'invalid domain name' => [
                'domain' => '[::1]',
                'expected' => "Domain to Unicode: [::1]\nDomain to Ascii: [::1]",
            ],
        ];
    }
}
