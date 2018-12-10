<?php

/**
 * Laravel Domain Parser Package (https://github.com/bakame-php/laravel-domain-parser).
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Illuminate\Console\Command;

final class RefreshCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'domain-parser:refresh {--rules} {--tlds}';

    /**
     * @var string
     */
    protected $description = 'Refresh the Domain Parser Cache.';

    /**
     * {@inheritdoc}
     */
    public function handle(): int
    {
        $this->info('Starting refreshing the Domain Parser Cache');

        $refreshTLDs = $this->option('tlds');
        $refreshRules = $this->option('rules');

        if (!$refreshTLDs && !$refreshRules) {
            $refreshRules = true;
            $refreshTLDs = true;
        }

        if ($refreshRules) {
            if (!Factory::refreshRules()) {
                $this->error('The Public Suffix List could not be refreshed. Please review your settings.');
                return 1;
            }

            $this->info('The Public Suffix List is refreshed.');
        }

        if (!$refreshTLDs) {
            return 0;
        }

        if (Factory::refreshTLDs()) {
            $this->info('The IANA Root Zone Database is refreshed.');
            return 0;
        }

        $this->error('The IANA Root Zone Database could not be refreshed. Please review your settings.');
        return 1;
    }
}
