<?php

return [
    /**
     * Name of the cache to use. If a string is given it will be one of
     * the store listed in the `stores` configuration array in 
     * your `cache` configuration file.
     */
    'cache_client' => 'file',
    /**
     * Cache TTL.
     * - Can be a DateInterval object
     * - If a DateTimeInterface object is given, the TTL will represents 
     *   the difference between the given object and the current timestamp
     * - If a string is given is must be usable by DateInterval::createFromDateString
     * - If an int is given, it is consireded as the TTL is seconds 
     */
    'cache_ttl' => '1 DAY',
    /**
     * Name of the HTTP Client to use. If a string is given it must be of 
     * of the following curl, guzzle.
     * Otherwise you can give an instance of a Pdp\HttpClient implementing object.
     */
    'http_client' => 'curl',
    /** 
     * HTTP client options (optional).
     * Additionals options to use when instantiating the curl and/or the guzzle client
     * For the curl client the options takes a array usable by curl_setopt_array
     * For the guzzle client the options will be used on instantiation
     * Not use or check if a Pdp\HttpClient implementing object is given to http_client.
     */
    'http_client_options' => [],
    /**
     * External Public Suffix List URL (optional)
     * If not present or equals to `null` the package will default to the official URL
     */
    'url_psl' => 'https://publicsuffix.org/list/public_suffix_list.dat',
    /**
     * External Root Zone Domain URL (optional)
     * If not present or equals to `null` the package will default to the official URL
     */
    'url_rzd' => 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
];