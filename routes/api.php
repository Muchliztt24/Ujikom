<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminPortalApiController;
use App\Http\Controllers\Api\Admin\AdminReviewApiController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\Uploader\UploaderChapterApiController;
use App\Http\Controllers\Api\Uploader\UploaderChapterImageApiController;
use App\Http\Controllers\Api\Uploader\UploaderPortalApiController;
use App\Http\Controllers\Api\WorkApiController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/genres', [WorkApiController::class, 'genres']);

Route::get('/auth/providers', [SocialAuthController::class, 'providers']);
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('api.auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('api.auth.social.callback');
Route::post('/auth/{provider}/token', [SocialAuthController::class, 'tokenLogin'])->name('api.auth.social.token');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::patch('/me', [AuthController::class, 'updateMe']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/works', [WorkApiController::class, 'index']);
Route::get('/works/{work}', [WorkApiController::class, 'show']);
Route::get('/works/{work}/chapters/{chapter}', [WorkApiController::class, 'chapter']);

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminPortalApiController::class, 'dashboard']);
        Route::get('/roles', [AdminPortalApiController::class, 'roles']);

        Route::get('/users', [AdminPortalApiController::class, 'users']);
        Route::get('/users/{user}', [AdminPortalApiController::class, 'showUser']);
        Route::patch('/users/{user}', [AdminPortalApiController::class, 'updateUser']);

        Route::get('/genres', [AdminPortalApiController::class, 'genres']);
        Route::post('/genres', [AdminPortalApiController::class, 'storeGenre']);
        Route::get('/genres/{genre}', [AdminPortalApiController::class, 'showGenre']);
        Route::patch('/genres/{genre}', [AdminPortalApiController::class, 'updateGenre']);
        Route::delete('/genres/{genre}', [AdminPortalApiController::class, 'destroyGenre']);

        Route::get('/works/pending', [AdminReviewApiController::class, 'pendingWorks']);
        Route::get('/works', [AdminReviewApiController::class, 'works']);
        Route::get('/works/{work}', [AdminReviewApiController::class, 'showWork']);
        Route::post('/works/{work}/approve', [AdminReviewApiController::class, 'approveWork']);
        Route::post('/works/{work}/reject', [AdminReviewApiController::class, 'rejectWork']);
        Route::delete('/works/{work}', [AdminReviewApiController::class, 'destroyWork']);

        Route::get('/chapters', [AdminReviewApiController::class, 'chapters']);
        Route::get('/chapters/{chapter}', [AdminReviewApiController::class, 'showChapter']);
        Route::delete('/chapters/{chapter}', [AdminReviewApiController::class, 'destroyChapter']);

        Route::get('/chapter-images', [AdminReviewApiController::class, 'chapterImages']);
        Route::get('/chapter-images/{chapterImage}', [AdminReviewApiController::class, 'showChapterImage']);
        Route::delete('/chapter-images/{chapterImage}', [AdminReviewApiController::class, 'destroyChapterImage']);
    });

Route::middleware(['auth:sanctum', 'role:uploader'])
    ->prefix('uploader')
    ->group(function () {
        Route::get('/dashboard', [UploaderPortalApiController::class, 'dashboard']);

        Route::get('/works', [UploaderPortalApiController::class, 'works']);
        Route::post('/works', [UploaderPortalApiController::class, 'storeWork']);
        Route::get('/works/{work}', [UploaderPortalApiController::class, 'showWork']);
        Route::patch('/works/{work}', [UploaderPortalApiController::class, 'updateWork']);
        Route::delete('/works/{work}', [UploaderPortalApiController::class, 'destroyWork']);
        Route::post('/works/{work}/submit', [UploaderPortalApiController::class, 'submitWork']);
        Route::get('/works/{work}/analytics', [UploaderPortalApiController::class, 'analytics']);

        Route::get('/works/{work}/chapters', [UploaderChapterApiController::class, 'index']);
        Route::post('/works/{work}/chapters', [UploaderChapterApiController::class, 'store']);
        Route::get('/works/{work}/chapters/{chapter}', [UploaderChapterApiController::class, 'show']);
        Route::patch('/works/{work}/chapters/{chapter}', [UploaderChapterApiController::class, 'update']);
        Route::delete('/works/{work}/chapters/{chapter}', [UploaderChapterApiController::class, 'destroy']);

        Route::get('/chapters/{chapter}/images', [UploaderChapterImageApiController::class, 'index']);
        Route::post('/chapters/{chapter}/images', [UploaderChapterImageApiController::class, 'store']);
        Route::get('/chapters/{chapter}/images/{chapterImage}', [UploaderChapterImageApiController::class, 'show']);
        Route::patch('/chapters/{chapter}/images/{chapterImage}', [UploaderChapterImageApiController::class, 'update']);
        Route::delete('/chapters/{chapter}/images/{chapterImage}', [UploaderChapterImageApiController::class, 'destroy']);
    });
