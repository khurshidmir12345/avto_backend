<?php

return [
    'avto_vodiy_phone' => env('AVTO_VODIY_PHONE', '+998000000001'),

    'welcome_message' => <<<'TEXT'
👋 Assalomu alaykum! **Avto Vodiy** ga xush kelibsiz 🚗

Savolingizni yozib qoldiring — admin albatta javob beradi.

⏳ Javob berish vaqti **1 minutdan 3 soatgacha** cho'zilishi mumkin.
Iltimos, xavotir olmang — sizga albatta javob beramiz 😊
TEXT,

    'media' => [
        'disk' => env('CHAT_MEDIA_DISK', 'r2'),
        'path_prefix' => 'chat',
        'image' => [
            'mimes' => ['jpeg', 'jpg', 'png', 'webp'],
            'max_size_kb' => 5120, // 5MB
        ],
        'voice' => [
            'mimes' => ['audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/aac'],
            'max_size_kb' => 5120, // 5MB
        ],
    ],
];
