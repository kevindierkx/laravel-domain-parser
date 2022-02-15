<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp\Commands;

use Bakame\Laravel\Pdp\Facades\DomainParser;
use Illuminate\Console\Command;
use Throwable;

class RefreshCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain-parser:refresh
                            {--rules}
                            {--tlds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh and update the PHP Domain Parser lists cache.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Refreshing PHP Domain Parser lists cache...');

        $refreshRulesList = $this->option('rules');
        $refreshTldList = $this->option('tlds');

        // Refresh both lists by default when no specific list is specified.
        if (! $refreshRulesList && ! $refreshTldList) {
            $refreshRulesList = true;
            $refreshTldList = true;
        }

        try {
            if ($refreshRulesList) {
                $this->updatePublicSuffixList();
            }

            if ($refreshTldList) {
                $this->updateTldList();
            }
        } catch (Throwable $e) {
            $this->error('The PHP Domain Parser lists cache could not be updated.');
            $this->error('An error occurred during the update.');
            $this->error('----- Error Message -----');
            $this->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * Update the public suffix list.
     *
     * @return bool
     */
    protected function updatePublicSuffixList(): bool
    {
        $this->info('Updating the Public Suffix list cache.');

        DomainParser::getRules(true);

        $this->info('The Public Suffix List cache is updated.');

        return true;
    }

    /**
     * Update the TLD list.
     *
     * @return bool
     */
    protected function updateTldList(): bool
    {
        $this->info('Updating the IANA Root Zone Database cache.');

        DomainParser::getTopLevelDomains(true);

        $this->info('The IANA Root Zone Database cache is updated.');

        return true;
    }
}
