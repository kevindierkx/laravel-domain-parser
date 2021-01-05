<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\DomainParser;
use Bakame\Laravel\Pdp\Facades\Rules;
use InvalidArgumentException;
use Pdp\ResolvedDomain;
use Pdp\ResourceUri;

class DomainParserConfigTest extends TestCase
{
    public function testUsingAnInvalidCacheStore(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->app['config']->set('domain-parser.cache_driver', 'foo');
        self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    }
}
