<?php

use App\Modules\Payments\Presentation\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payments/process', [PaymentController::class, 'process']);
