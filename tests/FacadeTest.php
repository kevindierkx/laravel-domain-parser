<?php

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\RulesFacade as Rules;
use Bakame\Laravel\Pdp\TopLevelDomainsFacade as TopLevelDomains;
use Pdp\Domain;

final class FacadeTest extends TestCase
{
    public function testRulesReturnsAnInstanceOfRules(): void
    {
        self::assertInstanceOf(Domain::class, Rules::resolve('bbc.co.uk'));
    }

    public function testTopLevelDomainsReturnsAnInstanceOfTopLevelDomains(): void
    {
        self::assertInstanceOf(Domain::class, TopLevelDomains::resolve('bbc.co.uk'));
    }
}
