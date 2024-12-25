<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'football-api' => [
        'key' => env('FOOTBALL_API_KEY'),
        'host' => 'api-football-v1.p.rapidapi.com',
        'base_url' => 'https://api-football-v1.p.rapidapi.com/v3',
        'rate_limit' => env('FOOTBALL_API_RATE_LIMIT', 30), // dakikada maksimum istek sayısı
    ],
];
