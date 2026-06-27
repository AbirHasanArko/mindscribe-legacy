<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('articles.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/articles/upload-image', [\App\Http\Controllers\ArticleController::class, 'uploadImage'])->name('articles.upload-image');
    Route::resource('articles', \App\Http\Controllers\ArticleController::class);
    Route::post('/articles/{article}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::post('/articles/{article}/ratings', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
});

Route::get('/author/{user}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('author.show');

require __DIR__.'/auth.php';
