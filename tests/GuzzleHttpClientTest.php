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

namespace BakameTest\Laravel\Pdp;

use Bakame\Laravel\Pdp\GuzzleHttpClient;
use GuzzleHttp\Client;
use Pdp\HttpClientException;
use PHPUnit\Framework\TestCase;

final class GuzzleHttpClientTest extends TestCase
{
    public function testGetContent(): void
    {
        $content = (new GuzzleHttpClient(new Client()))->getContent('https://www.google.com');
        self::assertContains('google', $content);
    }

    public function testThrowsException(): void
    {
        self::expectException(HttpClientException::class);
        (new GuzzleHttpClient(new Client()))->getContent('https://qsfsdfqdf.localhost');
    }
}
