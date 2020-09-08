<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Console\Command;
use Throwable;

final class RefreshCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'domain-parser:refresh {--rules} {--tlds}';

    /**
     * @var string
     */
    protected $description = 'Refresh and update PHP Domain Parser Local Database.';

    /**
     * {@inheritdoc}
     */
    public function handle(): int
    {
        $this->info('Starting refreshing PHP Domain Parser Local Database.');

        $refreshTLDs = $this->option('tlds');
        $refreshRules = $this->option('rules');

        if (! $refreshTLDs && ! $refreshRules) {
            $refreshRules = true;
            $refreshTLDs = true;
        }

        try {
            if ($refreshRules) {
                $this->info('Updating your Public Suffix List copy.');
                if (! Adapter::refreshRules()) {
                    $this->error('😓 😓 😓 Your Public Suffix List copy could not be updated. 😓 😓 😓');
                    $this->error('Please review your settings.');

                    return 1;
                }

                $this->info('💪 💪 💪 Your Public Suffix List copy is updated. 💪 💪 💪');
            }

            if (! $refreshTLDs) {
                return 0;
            }

            $this->info('Updating your IANA Root Zone Database copy.');
            if (Adapter::refreshTLDs()) {
                $this->info('💪 💪 💪 Your IANA Root Zone Database copy is updated. 💪 💪 💪');

                return 0;
            }

            $this->error('😓 😓 😓 Your IANA Root Zone Database copy could not be updated. 😓 😓 😓');
            $this->error('Please review your settings.');

            return 1;
        } catch (Throwable $e) {
            $this->error('😓 😓 😓 Your PHP Domain Parser Local Database could not be updated. 😓 😓 😓');
            $this->error('An error occurred during the update.');
            $this->error('----- Error Message ----');
            $this->error($e->getMessage());

            return 1;
        }
    }
}
