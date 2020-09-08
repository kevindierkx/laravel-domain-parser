<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use GuzzleHttp\Client;
use Pdp\HttpClient;
use Pdp\HttpClientException;
use Throwable;

final class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * New instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(string $url): string
    {
        try {
            return $this->client->get($url)->getBody()->getContents();
        } catch (Throwable $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
