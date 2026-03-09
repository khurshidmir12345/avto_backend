<?php

return [
    'link' => [
        'web_url' => env('TELEGRAM_LINK_WEB_URL', env('APP_URL', 'https://avtovodiy.uz')),
        'deep_link_scheme' => env('TELEGRAM_LINK_DEEP_SCHEME', 'avtovodiy'),
    ],
];
