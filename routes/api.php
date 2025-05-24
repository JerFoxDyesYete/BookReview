<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookReviewController;

// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/book-reviews', [BookReviewController::class, 'index'])->name('book-reviews.index');
Route::get('/book-reviews/{id}', [BookReviewController::class, 'show'])->name('book-reviews.show');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('book-reviews', BookReviewController::class)->except(['index', 'show']);
});
