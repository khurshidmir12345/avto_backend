<?php

return [
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
