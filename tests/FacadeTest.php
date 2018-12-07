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

use Pdp\Domain;
use Rules;
use TopLevelDomains;

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
