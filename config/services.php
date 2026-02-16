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

    'bots' => [
        'info_bot' => [
            'token' => env('TELEGRAM_INFO_LOG_BOT_TOKEN'),
            'chat_ids' => env('TELEGRAM_INFO_LOG_CHAT_IDS'),
        ],
        'error_bot' => [
            'token' => env('TELEGRAM_ERROR_LOG_BOT_TOKEN'),
            'chat_ids' => env('TELEGRAM_ERROR_LOG_CHAT_IDS'),
        ],
        'transactions_bot' => [
            'token' => env('TELEGRAM_TRANSACTIONS_LOG_BOT_TOKEN'),
            'chat_ids' => env('TELEGRAM_TRANSACTIONS_LOG_CHAT_IDS'),
        ],
        'admin_channel_bot' => [
            'token' => env('TELEGRAM_CHANNEL_ADMIN_BOT_TOKEN'),
            'chat_ids' => env('TELEGRAM_CHANNEL_ID'),
        ],
    ],

    'giga_chat' => [
        'key' => env('GIGA_CHAT_ASSISTANT_AUTH_KEY'),
        'client_id' => env('GIGA_CHAT_CLIENT_ID'),
        'scope' => env('GIGA_CHAT_SCOPE'),
        'model' => env('GIGA_CHAT_MODEL', 'model'),
        'model_name_for_balance' => env('GIGA_CHAT_MODEL_NAME_FOR_BALANCE'),
        'certificate_path' => env('GIGA_CHAT_CERT_PATH'),
        'pay_link' => env('GIGA_CHAT_PAY_TOKEN_LINK'),
        'auth_url' => env('GIGA_CHAT_AUTH_URL'),
        'api_url' => env('GIGA_CHAT_API_URL'),
        'timeout_request_seconds' => env('GIGA_CHAT_TIMEOUT_REQUEST_SECONDS'),
    ],

];
