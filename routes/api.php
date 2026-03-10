<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\MoshinaElonController;
use App\Http\Controllers\Api\TelegramController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);

// Telegram — webhook (CSRF dan mustasno)
Route::post('/telegram/webhook/{botType}', TelegramWebhookController::class)
    ->name('telegram.webhook');

// Telegram link info — auth shart emas (profil sahifasida bot linki ko'rsatish)
Route::get('/telegram/link-info', [TelegramController::class, 'linkInfo']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/telegram/link', [TelegramController::class, 'link']);
        Route::delete('/telegram/unlink', [TelegramController::class, 'unlink']);
        Route::get('/balance-history', [BalanceController::class, 'history']);
        Route::get('/elon-create-price', [BalanceController::class, 'elonCreatePrice']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/avatar', [AuthController::class, 'uploadAvatar']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
        Route::delete('/profile', [AuthController::class, 'deleteProfile']);
    });
});

Route::prefix('chat')->middleware('auth:sanctum')->group(function () {
    Route::get('/conversations', [ChatController::class, 'index']);
    Route::post('/conversations', [ChatController::class, 'store']);
    Route::get('/users', [ChatController::class, 'users']);
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'messages']);
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage']);
    Route::get('/media/{message}', [ChatController::class, 'media']);
});

Route::prefix('images')->middleware('auth:sanctum')->group(function () {
    Route::post('/presigned-url', [ImageController::class, 'presignedUrl']);
    Route::post('/save', [ImageController::class, 'save']);
    Route::delete('/{image}', [ImageController::class, 'deleteOrphanImage']);
});

// Reklamalar
Route::prefix('advertisements')->group(function () {
    Route::get('/', [AdvertisementController::class, 'index']);
    Route::post('/{advertisement}/view', [AdvertisementController::class, 'trackView']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/price', [AdvertisementController::class, 'price']);
        Route::get('/my', [AdvertisementController::class, 'myAds']);
        Route::post('/presigned-url', [AdvertisementController::class, 'presignedUrl']);
        Route::post('/', [AdvertisementController::class, 'store']);
        Route::post('/{advertisement}/reactivate', [AdvertisementController::class, 'reactivate']);
        Route::delete('/{advertisement}', [AdvertisementController::class, 'destroy']);
    });
});

Route::prefix('elonlar')->group(function () {
    Route::get('/', [MoshinaElonController::class, 'index']);
    Route::get('/{moshinaElon}/images', [MoshinaElonController::class, 'images']);
    Route::get('/{moshinaElon}', [MoshinaElonController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/my/list', [MoshinaElonController::class, 'myElonlar']);
        Route::post('/', [MoshinaElonController::class, 'store']);
        Route::put('/{moshinaElon}', [MoshinaElonController::class, 'update']);
        Route::delete('/{moshinaElon}', [MoshinaElonController::class, 'destroy']);
        Route::delete('/{moshinaElon}/images/{image}', [MoshinaElonController::class, 'deleteImage']);
        Route::put('/{moshinaElon}/images/reorder', [MoshinaElonController::class, 'reorderImages']);
    });
});
