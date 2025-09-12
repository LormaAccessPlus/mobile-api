<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ACCESS School Management System API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the ACCESS School Management System API integration.
    | These values should be provided by your hosting school.
    |
    */

    'application' => env('ACCESS_APPLICATION', ''),
    'school' => env('ACCESS_SCHOOL', ''),
    'key' => env('ACCESS_KEY', ''),
    'hash' => env('ACCESS_HASH', ''),
    'url' => env('ACCESS_URL', 'https://api.accessphp.net/'),
    'systemid' => env('ACCESS_SYSTEM_ID', ''),
    'debug' => env('ACCESS_DEBUG', false),
];