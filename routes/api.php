<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Profile & Logout
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// News (public routes)
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

// News (protected routes)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::match(['put', 'patch'], '/news/{news:slug}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news:slug}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::patch('/news/{news:slug}/toggle-visibility', [NewsController::class, 'toggleVisibility'])->name('news.toggle-visibility');
});
