<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | This is the key that will be used to sign your tokens. It should be a
    | long, random string. In a real environment, generate this via the
    | `php artisan jwt:secret` command.
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 60 minutes.
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // 14 days

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    */

    'algo' => env('JWT_ALGO', 'HS256'),
];

