<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Token
    |--------------------------------------------------------------------------
    |
    | Your Telegram bot token you received after creating
    | the chatbot through Telegram.
    |
    */
    'token' => env('TELEGRAM_TOKEN', ''),

    'name' => env('TELEGRAM_NAME', ''),

    'username' => env('TELEGRAM_USERNAME', ''),

    'hideInlineKeyboard' => true,

    'support_url' => env('TELEGRAM_SUPPORT_URL', 'https://t.me/faceoff'),
];
