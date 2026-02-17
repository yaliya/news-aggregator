<?php

return [
    /*
    |--------------------------------------------------------------------------
    | News Sources Configuration
    |--------------------------------------------------------------------------
    |
    | API keys and endpoints for the configured news providers. Keys are read
    | from the environment so they can be provided securely per deployment.
    |
    */

    'sources' => [
        'newsapi' => [
            'enabled' => env('NEWSAPI_ENABLED', true),
            'api_key' => env('NEWSAPI_KEY'),
            'endpoint' => env('NEWSAPI_ENDPOINT', 'https://newsapi.org/v2/top-headlines'),
            'default_params' => [
                'language' => env('NEWSAPI_LANGUAGE', 'en'),
                'country' => env("NEWSAPI_COUNTRY",'us')
            ],
        ],

        'guardian' => [
            'enabled' => env('GUARDIAN_ENABLED', true),
            'api_key' => env('GUARDIAN_KEY'),
            'endpoint' => env('GUARDIAN_ENDPOINT', 'https://content.guardianapis.com/search'),
            'default_params' => [
                'page-size' => 50,
            ],
        ],

        'nytimes' => [
            'enabled' => env('NYTIMES_ENABLED', true),
            'api_key' => env('NYTIMES_KEY'),
            'endpoint' => env('NYTIMES_ENDPOINT', 'https://api.nytimes.com/svc/search/v2/articlesearch.json'),
            'default_params' => [
                'sort' => 'newest',
            ],
        ],
    ],
];

