<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\Rules;
use Bakame\Laravel\Pdp\Facades\TopLevelDomains;
use Pdp\ResolvedDomain;

class FacadeTest extends TestCase
{
    public function testRulesFacadeReturnsAnInstanceOfRules(): void
    {
        self::assertInstanceOf(ResolvedDomain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testTopLevelDomainsFacadeReturnsAnInstanceOfTopLevelDomains(): void
    {
        self::assertInstanceOf(ResolvedDomain::class, TopLevelDomains::resolve('bbc.co.uk'));
    }
}
