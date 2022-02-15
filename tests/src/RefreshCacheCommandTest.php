<?php

namespace BakameTest\Laravel\Pdp;

class RefreshCacheCommandTest extends TestCase
{
    public const INFO_INTRO = 'Refreshing PHP Domain Parser lists cache...';
    public const INFO_INTRO_PUBLIC_SUFFIX_LIST = 'Updating the Public Suffix list cache.';
    public const INFO_OUTRO_PUBLIC_SUFFIX_LIST = 'The Public Suffix List cache is updated.';
    public const INFO_INTRO_TOP_LEVEL_DOMAINS_LIST = 'Updating the IANA Root Zone Database cache.';
    public const INFO_OUTRO_TOP_LEVEL_DOMAINS_LIST = 'The IANA Root Zone Database cache is updated.';
    public const ERROR_INTRO = 'The PHP Domain Parser lists cache could not be updated.';
    public const ERROR_DESCRIPTION = 'An error occurred during the update.';
    public const ERROR_HEADER = '----- Error Message -----';

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

    /**
     * @environment-setup useFaultyConfig
     */
    public function testExceptionsAreCaught(): void
    {
        $this->artisan('domain-parser:refresh')
            ->expectsOutput(self::INFO_INTRO)
            ->expectsOutput(self::ERROR_INTRO)
            ->expectsOutput(self::ERROR_DESCRIPTION)
            ->expectsOutput(self::ERROR_HEADER)
            ->assertExitCode(1);
    }

    /**
     * Define a faulty environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function useFaultyConfig($app): void
    {
        $app['config']->set('domain-parser.uri_public_suffix_list', 'https://example.com');
        $app['config']->set('domain-parser.uri_top_level_domain_list', 'https://example.com');
    }
}
