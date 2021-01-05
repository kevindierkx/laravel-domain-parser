<?php

declare(strict_types=1);

namespace Bakame\Laravel\Pdp;

use Bakame\Laravel\Pdp\Http\RequestFactory;
use Illuminate\Support\Facades\Cache;
use Pdp\PublicSuffixList;
use Pdp\ResourceUri;
use Pdp\Storage\PsrStorageFactory;
use Pdp\TopLevelDomainList;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

class DomainParser
{
    /**
     * @var \Bakame\Laravel\Pdp\DomainParserConfig
     */
    protected DomainParserConfig $config;

    /**
     * Create a new domain parser instance.
     *
     * @param \Bakame\Laravel\Pdp\DomainParserConfig $config
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
     * @return \Pdp\PublicSuffixList
     */
    public function getRules(bool $fresh = false): PublicSuffixList
    {
        $uri = $this->config->uriPublicSuffixList ?: $this->getDefaultPublicSuffixListUri();
        $ttl = $this->config->cacheTtl;

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
     * @return \Pdp\TopLevelDomainList
     */
    public function getTopLevelDomains(bool $fresh = false): TopLevelDomainList
    {
        $uri = $this->config->uriTopLevelDomainList ?: $this->getDefaultTopLevelDomainListUri();
        $ttl = $this->config->cacheTtl;

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
     * @return \Pdp\Storage\PsrStorageFactory
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
     * @return \Psr\SimpleCache\CacheInterface
     */
    private function getCacheInstance(): CacheInterface
    {
        return Cache::store($this->config->cacheDriver);
    }

    /**
     * Resolve the HTTP client instance that should be used by PDP.
     *
     * @return \Psr\Http\Client\ClientInterface
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
}
