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

use Artisan;

final class RefreshCacheCommandTest extends TestCase
{
    public function testRefreshRules(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh', ['--rules' => true]));
    }

    public function testRefreshTLDs(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh', ['--tlds' => true]));
    }

    public function testRefreshAll(): void
    {
        self::assertSame(0, Artisan::call('domain-parser:refresh'));
    }
}
