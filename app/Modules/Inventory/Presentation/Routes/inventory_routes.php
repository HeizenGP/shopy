<?php

use App\Modules\Inventory\Presentation\Controllers\AdminInventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:inventory.manage'])->prefix('admin/inventory')->group(function () {
    Route::get('/', [AdminInventoryController::class, 'index'])->name('admin.inventory.index');
    Route::post('/{id}', [AdminInventoryController::class, 'update'])->name('admin.inventory.update');
});
