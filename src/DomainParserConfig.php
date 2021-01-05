<?php

namespace Bakame\Laravel\Pdp;

class DomainParserConfig
{
    public ?string $cacheDriver = null;
    public ?int $cacheTtl = null;
    public array $httpClientOptions;
    public ?string $uriPublicSuffixList;
    public ?string $uriTopLevelDomainList;

    public function __construct(array $properties)
    {
        $this->cacheDriver = $properties['cache_driver'] ?? null;
        $this->cacheTtl = $properties['cache_ttl'] ?? null;
        $this->httpClientOptions = $properties['http_client_options'] ?? [];
        $this->uriPublicSuffixList = $properties['uri_public_suffix_list'] ?? null;
        $this->uriTopLevelDomainList = $properties['uri_top_level_domain_list'] ?? null;
    }
}
