<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookReviewController;

Route::middleware('api')->group(function () {
    Route::resource('book-reviews', BookReviewController::class);
});