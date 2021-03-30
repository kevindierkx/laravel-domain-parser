<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Bakame\Laravel\Pdp\Facades\Rules;
use Bakame\Laravel\Pdp\Facades\TopLevelDomains;
use Pdp\Domain;
use Pdp\Suffix;
use Throwable;

class Directives
{
    /**
     * Tells whether the submitted tld is a Top Level Domain
     * according to the IANA Root Database.
     *
     * @param mixed $tld
     *
     * @return bool
     */
    public static function isTLD($tld): bool
    {
        $tld = Suffix::fromUnknown($tld);

        foreach (TopLevelDomains::getIterator() as $knownTld) {
            if ($knownTld === $tld->toAscii()->toString()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the domain is a valid domain name.
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public static function isDomain($domain): bool
    {
        try {
            Rules::getICANNDomain($domain);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Determine if the domain contains an ICANN suffix.
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public static function isICANNSuffix($domain): bool
    {
        return Rules::resolve($domain)->suffix()->isICANN();
    }

    /**
     * Determine if the domain contains a private suffix.
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public static function isPrivateSuffix($domain): bool
    {
        return Rules::resolve($domain)->suffix()->isPrivate();
    }

    /**
     * Determine if the domain contains a known suffix.
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public static function isKnownSuffix($domain): bool
    {
        return Rules::resolve($domain)->suffix()->isKnown();
    }

    /**
     * Determine if the domain contains a known Top Level Domain suffix.
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public static function containsTLD($domain): bool
    {
        return TopLevelDomains::resolve($domain)->suffix()->isKnown();
    }

    /**
     * Convert the domain into its ASCII form.
     *
     * @param mixed $domain
     *
     * @return mixed
     */
    public static function toAscii($domain)
    {
        return Rules::resolve($domain)->toAscii()->toString() ?: $domain;
    }

    /**
     * Convert the domain into its unicode form.
     *
     * @param mixed $domain
     *
     * @return mixed
     */
    public static function toUnicode($domain)
    {
        return Rules::resolve($domain)->toUnicode()->toString() ?: $domain;
    }
}
