<?php

namespace BakameTest\Laravel\Pdp;

use Illuminate\Support\Facades\Artisan;
use Psr\SimpleCache\CacheInterface;

class RefreshCacheCommandTest extends TestCase
{
    const INFO_INTRO = 'Starting refreshing PHP Domain Parser lists cache...';
    const INFO_INTRO_PUBLIC_SUFFIX_LIST = 'Updating the Public Suffix list cache.';
    const INFO_OUTRO_PUBLIC_SUFFIX_LIST = 'The Public Suffix List cache is updated.';
    const INFO_INTRO_TOP_LEVEL_DOMAINS_LIST = 'Updating the IANA Root Zone Database cache.';
    const INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST = 'The IANA Root Zone Database cache is updated.';

    public function testRefreshRules(): void
    {
        $this->artisan('domain-parser:refresh', ['--rules' => true])
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_OUTRO_PUBLIC_SUFFIX_LIST)
            ->assertExitCode(0);
    }

    public function testRefreshTopLevelDomains(): void
    {
        $this->artisan('domain-parser:refresh', ['--tlds' => true])
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_TOP_LEVEL_DOMAINS_LIST)
            ->expectsOutput(self::INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST)
            ->assertExitCode(0);
    }

    public function testRefreshAll(): void
    {
        $this->artisan('domain-parser:refresh')
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_OUTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_INTRO_TOP_LEVEL_DOMAINS_LIST)
            ->expectsOutput(self::INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST)
            ->assertExitCode(0);
    }
}
