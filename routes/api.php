<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\MoshinaElonController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TelegramController;
use App\Http\Controllers\Api\PageViewController;
use App\Http\Controllers\Api\TelegramChannelController;
use App\Http\Controllers\Api\UserTelegramChannelController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/telegram-channels', [TelegramChannelController::class, 'index']);

// Telegram — webhook (CSRF dan mustasno)
Route::post('/telegram/webhook/{botType}', TelegramWebhookController::class)
    ->name('telegram.webhook');

// Telegram link info — auth shart emas
Route::get('/telegram/link-info', [TelegramController::class, 'linkInfo']);
Route::get('/support/bot-info', [TelegramController::class, 'supportBotInfo']);

Route::prefix('auth')->group(function () {
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    });

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

        Route::prefix('user-channels')->group(function () {
            Route::get('/', [UserTelegramChannelController::class, 'index']);
            Route::post('/', [UserTelegramChannelController::class, 'store']);
            Route::put('/{id}', [UserTelegramChannelController::class, 'update']);
            Route::delete('/{id}', [UserTelegramChannelController::class, 'destroy']);
            Route::post('/{id}/test', [UserTelegramChannelController::class, 'test']);
        });
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
    Route::post('/{advertisement}/view', [AdvertisementController::class, 'trackView'])
        ->middleware('throttle:30,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/price', [AdvertisementController::class, 'price']);
        Route::get('/my', [AdvertisementController::class, 'myAds']);
        Route::post('/presigned-url', [AdvertisementController::class, 'presignedUrl']);
        Route::post('/', [AdvertisementController::class, 'store']);
        Route::post('/{advertisement}/reactivate', [AdvertisementController::class, 'reactivate']);
        Route::delete('/{advertisement}', [AdvertisementController::class, 'destroy']);
    });
});

// Sevimlilar
Route::prefix('favorites')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/check/{elonId}', [FavoriteController::class, 'check']);
});

// Shikoyatlar
Route::prefix('reports')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ReportController::class, 'store']);
    Route::get('/my', [ReportController::class, 'myReports']);
});

// Bloklash
Route::prefix('blocked-users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [BlockController::class, 'index']);
    Route::post('/', [BlockController::class, 'store']);
    Route::delete('/{blockedUserId}', [BlockController::class, 'destroy']);
});

// Analitika — auth shart emas
Route::post('/page-views', [PageViewController::class, 'store'])
    ->middleware('throttle:60,1');

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
