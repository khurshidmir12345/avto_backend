<?php

use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\StorageProxyController;
use App\Http\Controllers\TelegramLinkController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Telegram profil ulash — web link (mobil da deep linkga redirect)
Route::get('/telegram-link', TelegramLinkController::class)->name('telegram.link');

// Admin chat media — rasm va voice xabarlar uchun
Route::get('/admin/chat/media/{message}', [AdminChatController::class, 'media'])
    ->middleware(['web', 'auth', EnsureUserIsAdmin::class])
    ->name('admin.chat.media');

// R2 rasmlarini proxy orqali berish (R2_PUBLIC_URL bo'sh bo'lsa)
Route::get('/media/{path}', [StorageProxyController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');
