<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used.
    | Available caching connections can be found in your applications cache.php
    | config.
    |
    | Refer to the docs for more information: https://laravel.com/docs/cache
    |
    */

    'cache_driver' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    |
    */

    'cache_ttl' => 3600,

    /**
     * HTTP client options (optional).
     * Additionals options to use when instantiating the curl and/or the guzzle client
     * For the curl client the options takes a array usable by curl_setopt_array
     * For the guzzle client the options will be used on instantiation
     * Not use or check if a Pdp\HttpClient implementing object is given to http_client.
     */

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
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
