<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserFeedController;
use App\Http\Controllers\UserPreferencesController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::middleware(['log.api', 'validate.news.source', 'throttle:60,1'])->group(function () {
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{article}', [ArticleController::class, 'show']);
});

Route::middleware('auth:api')->group(function () {
    Route::put('user/preferences', [UserPreferencesController::class, 'update']);
    Route::get('user/feed', [UserFeedController::class, 'index'])
        ->middleware('ensure.preferences');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

