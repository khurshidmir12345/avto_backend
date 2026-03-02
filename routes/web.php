<?php

use App\Http\Controllers\StorageProxyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// R2 rasmlarini proxy orqali berish (R2_PUBLIC_URL bo'sh bo'lsa)
Route::get('/media/{path}', [StorageProxyController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');
