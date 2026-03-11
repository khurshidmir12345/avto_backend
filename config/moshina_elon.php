<?php

return [

    /*
    |--------------------------------------------------------------------------
    | E'lon holatlari
    |--------------------------------------------------------------------------
    */
    'holatlar' => [
        'active' => 'active',
        'sold' => 'sold',
        'inactive' => 'inactive',
    ],

    /*
    |--------------------------------------------------------------------------
    | Valyuta turlari
    |--------------------------------------------------------------------------
    */
    'valyutalar' => ['USD', 'UZS'],

    /*
    |--------------------------------------------------------------------------
    | Yoqilg'i turlari
    |--------------------------------------------------------------------------
    */
    'yoqilgi_turlari' => [
        'benzin' => 'Benzin',
        'benzin+metan' => 'Benzin + Metan',
        'benzin+propan' => 'Benzin + Propan',
        'dizel' => 'Dizel',
        'salarka' => 'Salarka',
        'eloktor' => 'Eloktor',
        'gibrid' => 'Gibrid',
    ],

    /*
    |--------------------------------------------------------------------------
    | Uzatish qutisi turlari
    |--------------------------------------------------------------------------
    */
    'uzatish_qutisi_turlari' => ['mexanika', 'avtomat'],

    /*
    |--------------------------------------------------------------------------
    | Rasm yuklash sozlamalari
    |--------------------------------------------------------------------------
    */
    'images' => [
        'disk' => env('ELON_IMAGES_DISK', 'r2'),
        'mimes' => ['jpeg', 'jpg', 'png', 'webp', 'gif', 'bmp', 'heic', 'heif', 'tiff', 'svg'],
        'max_size_kb' => 10240, // 10MB
        'cdn_url' => env('IMAGE_CDN_URL', 'https://img.avtovodiy.uz'),
        'presigned_expiry_minutes' => env('IMAGE_PRESIGNED_EXPIRY_MINUTES', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Validatsiya chegaralari
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'yil_min' => 1990,
        'yil_max_offset' => 1, // joriy yil + 1
        'probeg_min' => 0,
        'narx_min' => 0,
        'marka_max' => 100,
        'model_max' => 100,
        'rang_max' => 50,
        'kraska_holati_max' => 255,
        'shahar_max' => 100,
        'tavsif_max' => 5000,
        'telefon_regex' => '/^998[0-9]{9}$/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Paginatsiya
    |--------------------------------------------------------------------------
    */
    'per_page' => 15,
    'per_page_max' => 50,

];
