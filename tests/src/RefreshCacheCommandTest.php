<?php

namespace BakameTest\Laravel\Pdp;

class RefreshCacheCommandTest extends TestCase
{
    const INFO_INTRO = 'Starting refreshing PHP Domain Parser lists cache...';
    const INFO_INTRO_PUBLIC_SUFFIX_LIST = 'Updating the Public Suffix list cache.';
    const INFO_OUTRO_PUBLIC_SUFFIX_LIST = 'The Public Suffix List cache is updated.';
    const INFO_INTRO_TOP_LEVEL_DOMAINS_LIST = 'Updating the IANA Root Zone Database cache.';
    const INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST = 'The IANA Root Zone Database cache is updated.';
    const ERROR_INTRO = 'The PHP Domain Parser lists cache could not be updated.';
    const ERROR_DESCRIPTION = 'An error occurred during the update.';
    const ERROR_HEADER = '----- Error Message -----';

    public function testRefreshingRulesOnly(): void
    {
        $this->artisan('domain-parser:refresh', ['--rules' => true])
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_OUTRO_PUBLIC_SUFFIX_LIST)
            ->assertExitCode(0);
    }

    public function testRefreshingTldsOnly(): void
    {
        $this->artisan('domain-parser:refresh', ['--tlds' => true])
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_TOP_LEVEL_DOMAINS_LIST)
            ->expectsOutput(self::INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST)
            ->assertExitCode(0);
    }

    public function testRefreshingAll(): void
    {
        $this->artisan('domain-parser:refresh')
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::INFO_INTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_OUTRO_PUBLIC_SUFFIX_LIST)
            ->expectsOutput(self::INFO_INTRO_TOP_LEVEL_DOMAINS_LIST)
            ->expectsOutput(self::INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST)
            ->assertExitCode(0);
    }

    public function testExceptionsAreCaught(): void
    {
        $this->app['config']->set('domain-parser.url_psl', 'https://example.com');
        $this->app['config']->set('domain-parser.url_rzd', 'https://example.com');

        $this->artisan('domain-parser:refresh')
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::ERROR_INTRO)
            ->expectsOutput(self::ERROR_DESCRIPTION)
            ->expectsOutput(self::ERROR_HEADER)
            ->assertExitCode(1);
    }
}
