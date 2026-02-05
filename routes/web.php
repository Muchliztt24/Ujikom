<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{WorkController, ChapterController, ChapterImageController, BookmarkController, CommentController, GenreController};

use App\Http\Controllers\Admin\{WorkApprovalController, UserController, AdminWorkController, AdminChapterController, AdminChapterImageController};

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS (SEMUA YANG LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | WORKS & CHAPTERS (Uploader / Author)
    |--------------------------------------------------------------------------
    */
    Route::resource('works', WorkController::class);
    Route::resource('works.chapters', ChapterController::class);
    Route::resource('chapters.images', ChapterImageController::class);

    // Submit karya ke admin (draft → pending)
    Route::post('/works/{work}/submit', [WorkController::class, 'submit'])->name('works.submit');

    /*
    |--------------------------------------------------------------------------
    | USER FEATURES
    |--------------------------------------------------------------------------
    */
    // Bookmark
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    Route::post('/works/{work}/bookmark', [BookmarkController::class, 'store'])->name('bookmarks.store');

    Route::delete('/works/{work}/bookmark', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Comment
    Route::post('/chapters/{chapter}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('users', UserController::class)->except(['show', 'create', 'store', 'destroy']);
        Route::resource('genres', GenreController::class);
        /*
        |--------------------------------------------------------------------------
        | WORK APPROVAL
        |--------------------------------------------------------------------------
        */
        Route::get('/works/pending', [WorkApprovalController::class, 'index'])->name('works.pending');

        Route::get('/works/{work}', [WorkApprovalController::class, 'show'])->name('works.show');

        Route::post('/works/{work}/approve', [WorkApprovalController::class, 'approve'])->name('works.approve');

        Route::post('/works/{work}/reject', [WorkApprovalController::class, 'reject'])->name('works.reject');
    });
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/works', [AdminWorkController::class, 'index'])->name('works.index');

        Route::get('/works/{work}', [AdminWorkController::class, 'show'])->name('works.show');

        Route::post('/works/{work}/approve', [AdminWorkController::class, 'approve'])->name('works.approve');

        Route::post('/works/{work}/reject', [AdminWorkController::class, 'reject'])->name('works.reject');

        Route::delete('/works/{work}', [AdminWorkController::class, 'destroy'])->name('works.destroy');
        /*
        |--------------------------------------------------------------------------
        | MODERASI WORK (CHAPTER & KOMIK)
        |--------------------------------------------------------------------------
        */

        // Moderasi Chapter (Novel & Komik)
        Route::get('/chapters', [AdminChapterController::class, 'index'])->name('chapters.index');

        Route::get('/chapters/{chapter}', [AdminChapterController::class, 'show'])->name('chapters.show');

        Route::delete('/chapters/{chapter}', [AdminChapterController::class, 'destroy'])->name('chapters.destroy');

        // Moderasi Chapter Images (Komik)
        Route::get('/chapter-images', [AdminChapterImageController::class, 'index'])->name('chapter-images.index');

        Route::get('/chapter-images/{chapterImage}', [AdminChapterImageController::class, 'show'])->name('chapter-images.show');

        Route::delete('/chapter-images/{chapterImage}', [AdminChapterImageController::class, 'destroy'])->name('chapter-images.destroy');
    });

Route::get('/explore', [WorkController::class, 'publicIndex'])->name('works.explore');

Route::get('/works/{work}', [WorkController::class, 'publicShow'])->name('works.public.show');

/*
|--------------------------------------------------------------------------
| DEBUG (OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::get('/cek-role', function () {
    dd(auth()->user()->role->name);
})->middleware('auth');
