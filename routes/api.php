<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MoshinaElonController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
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
