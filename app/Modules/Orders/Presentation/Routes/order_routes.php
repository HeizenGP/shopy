<?php

use App\Modules\Orders\Presentation\Controllers\OrderController;
use App\Modules\Orders\Presentation\Controllers\AdminOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('store.access')->prefix('checkout')->group(function () {
    Route::get('/', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/', [OrderController::class, 'placeOrder'])->name('checkout.place');
});

Route::middleware('store.access')->group(function () {
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'permission:orders.manage'])->prefix('admin/orders')->group(function () {
    Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
});
