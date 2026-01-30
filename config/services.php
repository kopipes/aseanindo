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

    'agora' => [
        'app_id' => env('AGORA_APP_ID'),
        'app_certificate' => env('AGORA_APP_CERTIFICATE'),
        'api_key' => env('AGORA_API_KEY'),
        'api_secret' => env('AGORA_API_SECRET'),

        'storage' => [
            'access_key' => env('AWS_ACCESS_KEY_ID'),
            'secret_key' => env('AWS_SECRET_ACCESS_KEY'),
            'bucket' => env('AWS_AGORA_BUCKET')
        ],
        'record_url' => env('AWS_AGORA_URL')
    ],

    'socket_broadcast' => [
        'url' => env('SOCKET_BROADCAST_URL'),
        'token' => env('SOCKET_BROADCAST_TOKEN'),
    ],

    'firebase_key' => env('GOOGLE_FCM_KEY'),

    'api' => [
        'jwt_secret_key' => env('JWT_SECRET_KEY'),
        'secret_key' => env('API_SECRET_KEY'),
        'validate_blacklist' => false
    ],

    'nexmo' => [
        'api_key' => env('NEXMO_API_KEY'),
        'api_secret' => env('NEXMO_API_SECRET'),
        'signature' => env('NEXMO_SIGNATURE'),
    ],

    'sip' => [
        'api_url' => env('SIP_ENDPOINT'),
        'api_key' => env('SIP_API_KEY'),
        'record_url' => env('SIP_RECORDING_URL')
    ],

    'landing_page' => env('LANDING_PAGE_URL'),


    'placeholder_avatar' => 'https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png',


    'term_condition' => 'https://kontakami.com/terms-condition',
    'privacy_policy' => 'https://kontakami.com/privacy-policy',

    'crypto_key' => env('CRYPTO_KEY')

];
