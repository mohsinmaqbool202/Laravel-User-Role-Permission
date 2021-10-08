<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'google' => [
        'client_id' => '249784527040-6cfcechtjtsbck13hmkjfite6lmjhugc.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

    'facebook' => [
     'client_id' => '291622595875258',
     'client_secret' => '5b7f692b4f02bb525975e83e99347a0a',
     'redirect' => 'http://localhost:8000/auth/callback/facebook',
   ], 

];
