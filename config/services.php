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

    'giga_chat' => [
        'key' => env('GIGA_CHAT_ASSISTANT_AUTH_KEY', 'key'),
        'client_id' => env('GIGA_CHAT_CLIENT_ID', 'id'),
        'scope' => env('GIGA_CHAT_SCOPE', 'scope'),
        'model' => env('GIGA_CHAT_MODEL', 'model'),
        'model_name_for_balance' => env('GIGA_CHAT_MODEL_NAME_FOR_BALANCE', 'model'),
        'certificate_path' => env('GIGA_CHAT_CERT_PATH', 'path'),
        'pay_link' => env('GIGA_CHAT_PAY_TOKEN_LINK', 'path'),
    ],

];
