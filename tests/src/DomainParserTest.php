<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\DomainParser;
use Pdp\ResourceUri;

class DomainParserTest extends TestCase
{
    public function testTheDefaultPublicSuffixListUriMatchesPdp(): void
    {
        self::assertEquals(DomainParser::getDefaultPublicSuffixListUri(), ResourceUri::PUBLIC_SUFFIX_LIST_URI);
    }

    public function testTheTopLevelDomainListUriMatchesPdp(): void
    {
        self::assertEquals(DomainParser::getDefaultTopLevelDomainListUri(), ResourceUri::TOP_LEVEL_DOMAIN_LIST_URI);
    }

    public function testConvertingATtlToDateTimeUsingNullReturnsNull(): void
    {
        self::assertNull($this->callMethod(app('pdp.parser'), 'convertTtlToDateTime', [null]));
    }
}
