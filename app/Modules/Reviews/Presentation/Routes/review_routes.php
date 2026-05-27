<?php

use App\Modules\Reviews\Presentation\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
