<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile/{username}', [InstagramController::class, 'getProfile'])
    ->name('profile.get');

Route::get('/post/{username}/{code}', [InstagramController::class, 'viewPost'])
    ->name('post.show');

Route::post('/post/{id}/{username}/like', [InstagramController::class, 'boostLike'])
    ->name('post.like');

Route::post('/post/{postId}/{username}/boost', [PostController::class, 'boost'])->name('post.boost');

