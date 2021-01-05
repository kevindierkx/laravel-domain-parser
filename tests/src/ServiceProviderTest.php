<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\DomainParser;
use Pdp\Rules as PdpRules;
use Pdp\TopLevelDomains as PdpTLD;

class ServiceProviderTest extends TestCase
{
    public function testExpectedServicesArePopulated(): void
    {
        self::assertTrue($this->app->bound('pdp.parser'));
        self::assertInstanceOf(DomainParser::class, $this->app->make('pdp.parser'));

        self::assertTrue($this->app->bound('pdp.rules'));
        self::assertInstanceOf(PdpRules::class, $this->app->make('pdp.rules'));

        self::assertTrue($this->app->bound('pdp.tld'));
        self::assertInstanceOf(PdpTLD::class, $this->app->make('pdp.tld'));
    }
}
