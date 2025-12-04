<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProfileController;

// Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Profile & Logout
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::apiResource('news', NewsController::class)->parameters(['news' => 'news:slug']);
    
    // toggle visibility of news
    Route::patch('news/{news:slug}/toggle-visibility', [NewsController::class, 'toggleVisibility'])
        ->name('news.toggle-visibility');
});
