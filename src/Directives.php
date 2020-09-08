<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Bakame\Laravel\Pdp\RulesFacade as Rules;
use Bakame\Laravel\Pdp\TopLevelDomainsFacade as TopLevelDomains;
use Pdp\Domain;
use Throwable;

final class Directives
{
    /**
     * Tells whether the submitted tld is a Top Level Domain
     * according to the IANA Root Database.
     *
     * @param mixed $tld
     */
    public static function isTLD($tld): bool
    {
        return TopLevelDomains::contains($tld);
    }

    /**
     * Tells whether the submitted domain is a domain name.
     *
     * @param mixed $domain
     */
    public static function isDomain($domain): bool
    {
        try {
            new Domain($domain);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Tells whether the submitted domain contains a known suffix.
     *
     * @param mixed $domain
     */
    public static function isKnownSuffix($domain): bool
    {
        return Rules::resolve($domain)->isKnown();
    }

    /**
     * Tells whether the submitted domain contains an ICANN suffix.
     *
     * @param mixed $domain
     */
    public static function isICANNSuffix($domain): bool
    {
        return Rules::resolve($domain)->isICANN();
    }

    /**
     * Tells whether the submitted domain contains an private suffix.
     *
     * @param mixed $domain
     */
    public static function isPrivateSuffix($domain): bool
    {
        return Rules::resolve($domain)->isPrivate();
    }

    /**
     * Tells whether the submitted domain contains an a Top Level Domain.
     *
     * @param mixed $domain
     */
    public static function containsTLD($domain): bool
    {
        try {
            return TopLevelDomains::contains((new Domain($domain))->getLabel(0));
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Converts the domain into its ascii form.
     *
     * If the domain is not a valid domain name it will be
     * returned as is otherwise the domain name is normalized
     * into its lowercased ascii representation.
     *
     * @param mixed $domain
     *
     * @return mixed
     */
    public static function toAscii($domain)
    {
        try {
            return (new Domain($domain))->toAscii()->getContent() ?? $domain;
        } catch (Throwable $e) {
            return $domain;
        }
    }

    /**
     * Converts the domain into its unicode form.
     *
     * If the domain is not a valid domain name it will be
     * returned as is otherwise the domain name is normalized
     * into its lowercased unicode representation.
     *
     * @param mixed $domain
     *
     * @return mixed
     */
    public static function toUnicode($domain)
    {
        try {
            return (new Domain($domain))->toUnicode()->getContent() ?? $domain;
        } catch (Throwable $e) {
            return $domain;
        }
    }
}
