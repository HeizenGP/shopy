<?php

use App\Modules\Coupons\Presentation\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);
