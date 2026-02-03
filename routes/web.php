<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChapterImageController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| UPLOADER AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::resource('works', WorkController::class);
    Route::resource('works.chapters', ChapterController::class);
    Route::resource('chapters.images', ChapterImageController::class);

});

Route::middleware('auth')->group(function () {

    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/works/{work}/bookmark', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/works/{work}/bookmark', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Comments
    Route::post('/chapters/{chapter}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/cek-role', function () {
    dd(auth()->user()->role->name);
})->middleware('auth');

