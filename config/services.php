<?php

return [

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

'vnpay' => [
  'tmn_code' => env('VNPAY_TMN_CODE'),
  'hash_secret' => env('VNPAY_HASH_SECRET'),
  'url' => env('VNPAY_URL'),
  'return_url' => env('VNPAY_RETURN_URL'),
  'ipn_url' => env('VNPAY_IPN_URL'),
],

    // THÊM PHẦN NÀY ↓↓↓
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'gmail' => [
        'client_id' => env('GOOGLE_MAILER_CLIENT_ID'),
        'client_secret' => env('GOOGLE_MAILER_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_MAILER_REDIRECT_URI'),
    ],
    
];
