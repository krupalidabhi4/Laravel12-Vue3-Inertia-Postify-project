<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::resource('users', UserController::class);

    Route::controller(PostController::class)->prefix('post')->group(function () {
        Route::get('/create', 'create')->name('post.create');
        Route::post('/store', 'store')->name('post.store');
        Route::get('/index', 'index')->name('post.index');
        Route::get('/edit/{id}', 'edit')->name('post.edit');
        Route::post('/update/{id}', 'update')->name('post.update');
        Route::get('/listing', 'listing')->name('post.listing');
        Route::get('/show/{post}', 'show')->name('post.show');
        Route::post('/like/{post}', 'toggleLike')->name('post.like');
        Route::post('/comment/{post}', 'comment')->name('post.comment');
        Route::post('/comment-update/{id}', 'updateComment')->name('post.comment-update');
    });

});


require __DIR__.'/auth.php';
