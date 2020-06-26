<?php

/**
 * Laravel Domain Parser Package (https://github.com/bakame-php/laravel-domain-parser)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Pdp\Domain;
use Rules;
use Throwable;
use TopLevelDomains;

final class Directives
{
    /**
     * Tells whether the submitted tld is a Top Level Domain
     * according to the IANA Root Database.
     */
    public static function isTLD($tld): bool
    {
        return TopLevelDomains::contains($tld);
    }

    /**
     * Tells whether the submitted domain is a domain name.
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
     */
    public static function isKnownSuffix($domain): bool
    {
        return Rules::resolve($domain)->isKnown();
    }

    /**
     * Tells whether the submitted domain contains an ICANN suffix.
     */
    public static function isICANNSuffix($domain): bool
    {
        return Rules::resolve($domain)->isICANN();
    }

    /**
     * Tells whether the submitted domain contains an private suffix.
     */
    public static function isPrivateSuffix($domain): bool
    {
        return Rules::resolve($domain)->isPrivate();
    }

    /**
     * Tells whether the submitted domain contains an a Top Level Domain.
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
