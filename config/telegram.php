<?php

return [

    'link' => [
        'web_url' => env('TELEGRAM_LINK_WEB_URL', env('APP_URL', 'https://avtovodiy.uz')),
        'deep_link_scheme' => env('TELEGRAM_LINK_DEEP_SCHEME', 'avtovodiy'),
    ],

    'app_links' => [
        'android' => env('APP_LINK_ANDROID', 'https://play.google.com/store/apps/details?id=uz.avtovodiy'),
        'iphone' => env('APP_LINK_IPHONE', 'https://apps.apple.com/us/app/avto-vodiy/id6760177390'),
    ],

    'channel' => [
        'disclaimer' => "⚠️ Эслатма:\nМашинани кўрмасдан пул ташламанг.\nКанал савдога масъул эмас.",
    ],

];
