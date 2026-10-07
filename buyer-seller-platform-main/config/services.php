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

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', rtrim((string) env('APP_URL'), '/') . '/auth/google/callback'),
    ],

    'google_translate' => [
        'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
    ],

    'currency_api' => [
        'key' => env('CURRENCY_API_KEY'),
        'base_url' => env('CURRENCY_API_BASE_URL', 'https://api.currencyapi.com'),
    ],

    'nimbus_sms' => [
        'api_key' => env('NIMBUS_SMS_API_KEY'),
        'user_id' => env('NIMBUS_SMS_USER_ID', 'nathoastobiz'),
        'password' => env('NIMBUS_SMS_PASSWORD', 'Amityadav@658'),
        'sender_id' => env('NIMBUS_SMS_SENDER_ID'),
        'template_id' => env('NIMBUS_SMS_TEMPLATE_ID'),
        'pe_id' => env('NIMBUS_SMS_PE_ID'),
        'message_template' => env('NIMBUS_SMS_MESSAGE_TEMPLATE', 'Your OTP for login is :otp. Please do not share it with anyone.'),
        'api_url' => env('NIMBUS_SMS_API_URL', 'http://nimbusit.biz/api/smsapi'),
    ],

];
