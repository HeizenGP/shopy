<?php

use App\Modules\Coupons\Presentation\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

Route::middleware('store.access')->prefix('coupon')->group(function () {
    Route::post('/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/remove', [CouponController::class, 'remove'])->name('coupon.remove');
});
