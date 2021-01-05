<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\DomainParser;
use Bakame\Laravel\Pdp\Facades\Rules;
use InvalidArgumentException;
use Pdp\ResolvedDomain;
use Pdp\ResourceUri;
use RuntimeException;

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
}
