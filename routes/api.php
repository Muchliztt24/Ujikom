<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\WorkApiController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/providers', [SocialAuthController::class, 'providers']);
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('api.auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('api.auth.social.callback');
Route::post('/auth/{provider}/token', [SocialAuthController::class, 'tokenLogin'])->name('api.auth.social.token');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/works', [WorkApiController::class, 'index']);
Route::get('/works/{work}', [WorkApiController::class, 'show']);
Route::get('/works/{work}/chapters/{chapter}', [WorkApiController::class, 'chapter']);
