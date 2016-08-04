<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT'),
    ],

    'qq' => [
        'client_id'     => env('QQ_APP_ID'),
        'client_secret' => env('QQ_APP_SECRET'),
        'redirect'      => env('QQ_REDIRECT'),
    ],

    'github' => [
        'client_id'     => '6be20dc1aaf15dd772e3',
        'client_secret' => '8fcc78cbd7e29fa6c2ccc65bdb6ed699eb26827e',
        'redirect'      => 'http://localhost:8888/github/callback',
    ]
];
