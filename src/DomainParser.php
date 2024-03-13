<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Bakame\Laravel\Pdp\Http\RequestFactory;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Pdp\PublicSuffixList;
use Pdp\ResourceUri;
use Pdp\Storage\PsrStorageFactory;
use Pdp\TopLevelDomainList;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;

class DomainParser
{
    /**
     * @var DomainParserConfig
     */
    protected DomainParserConfig $config;

    /**
     * Create a new domain parser instance.
     *
     * @param DomainParserConfig $config
     */
    public function __construct(DomainParserConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Get the Public Suffix List instance.
     *
     * @param bool $fresh
     *
     * @return PublicSuffixList
     */
    public function getRules(bool $fresh = false): PublicSuffixList
    {
        $uri = $this->config->uriPublicSuffixList ?: $this->getDefaultPublicSuffixListUri();
        $ttl = $this->convertTtlToDateTime($this->config->cacheTtl);

        $factory = $this->getStorageFactory();
        $storage = $factory->createPublicSuffixListStorage('', $ttl);

        if ($fresh) {
            $storage->delete($uri);
        }

        return $storage->get($uri);
    }

    /**
     * Get the Top Level Domains List instance.
     *
     * @param bool $fresh
     *
     * @return TopLevelDomainList
     */
    public function getTopLevelDomains(bool $fresh = false): TopLevelDomainList
    {
        $uri = $this->config->uriTopLevelDomainList ?: $this->getDefaultTopLevelDomainListUri();
        $ttl = $this->convertTtlToDateTime($this->config->cacheTtl);

        $factory = $this->getStorageFactory();
        $storage = $factory->createTopLevelDomainListStorage('', $ttl);

        if ($fresh) {
            $storage->delete($uri);
        }

        return $storage->get($uri);
    }

    /**
     * Resolve the PDP storage factory instance.
     *
     * @return PsrStorageFactory
     */
    private function getStorageFactory(): PsrStorageFactory
    {
        return new PsrStorageFactory(
            $this->getCacheInstance(),
            $this->getHttpClientInstance(),
            new RequestFactory()
        );
    }

    /**
     * Resolve the cache instance that should be used by PDP.
     *
     * @return CacheInterface
     */
    private function getCacheInstance(): CacheInterface
    {
        return Cache::store($this->config->cacheDriver);
    }

    /**
     * Resolve the HTTP client instance that should be used by PDP.
     *
     * @return ClientInterface
     */
    private function getHttpClientInstance(): ClientInterface
    {
        return new \GuzzleHttp\Client($this->config->httpClientOptions);
    }

    /**
     * Get the default URI used to fetch the public suffix list.
     *
     * @return string
     */
    public function getDefaultPublicSuffixListUri(): string
    {
        return ResourceUri::PUBLIC_SUFFIX_LIST_URI;
    }

    /**
     * Get the default URI used to fetch the Top Level Domain list.
     *
     * @return string
     */
    public function getDefaultTopLevelDomainListUri(): string
    {
        return ResourceUri::TOP_LEVEL_DOMAIN_LIST_URI;
    }

    /**
     * Convert a TTL into a date time object.
     *
     * @param int|null $ttl
     *
     * @return DateTime
     */
    protected function convertTtlToDateTime(?int $ttl = null): ?DateTime
    {
        if (is_null($ttl)) {
            return null;
        }

        return (new DateTime())->modify("+{$ttl} minutes");
    }
}
