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
    | Expected: string|null
    |
    */

    'cache_driver' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    |
    | The cache TTL determines how long, in minutes, the PSL and TLD lists are
    | kept in cache before fetching new lists.
    |
    | Expected: int|null
    |
    */

    'cache_ttl' => 3600,

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
    | These are optional GuzzleHttp Client configurations. For a full list of
    | available options please refer to the official docs: https://docs.guzzlephp.org/en/7.0/quickstart.html
    |
    | Expected: array
    |
    */

    'http_client_options' => [],

    /*
    |--------------------------------------------------------------------------
    | Public Suffix List URI
    |--------------------------------------------------------------------------
    |
    | This option controls the default location the package will use to fetch
    | a fresh PSL list.
    |
    | Expected: string|null
    |
    */

    'uri_public_suffix_list' => 'https://publicsuffix.org/list/public_suffix_list.dat',

    /*
    |--------------------------------------------------------------------------
    | Top Level Domain list URI
    |--------------------------------------------------------------------------
    |
    | This option controls the default location the package will use to fetch
    | a fresh TLD list.
    |
    | Expected: string|null
    |
    */

    'uri_top_level_domain_list' => 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
];
