<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sso' => [
        'sinergi' => [
            'client_id' => env('SINERGI_CLIENT_ID'),
            'client_secret' => env('SINERGI_CLIENT_SECRET'),
            'redirect' => env('SINERGI_REDIRECT_URI'),
            'base_url' => env('SINERGI_BASE_URL', 'https://sso.sinergi.go.id'),
        ],
        'disimuti' => [
            'client_id' => env('DISIMUTI_CLIENT_ID'),
            'client_secret' => env('DISIMUTI_CLIENT_SECRET'),
            'redirect' => env('DISIMUTI_REDIRECT_URI'),
            'base_url' => env('DISIMUTI_BASE_URL', 'https://sso.disimuti.go.id'),
        ],
    ],

];
