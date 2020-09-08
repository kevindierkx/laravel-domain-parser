<?php

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
        self::assertStringContainsString('google', $content);
    }

    public function testThrowsException(): void
    {
        self::expectException(HttpClientException::class);
        (new GuzzleHttpClient(new Client()))->getContent('https://qsfsdfqdf.localhost');
    }
}
