<?php

use App\Catalog\Presentation\Controllers\AdminBrandController;
use App\Catalog\Presentation\Controllers\AdminCategoryController;
use App\Catalog\Presentation\Controllers\AdminProductController;
use App\Catalog\Presentation\Controllers\AdminProductVariantController;
use App\Catalog\Presentation\Controllers\PublicCategoryController;
use App\Catalog\Presentation\Controllers\PublicProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicProductController::class, 'home'])->name('home');

Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [PublicProductController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [PublicCategoryController::class, 'show'])->name('categories.show');

Route::prefix('admin/catalog')->name('admin.catalog.')->group(function (): void {
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
    Route::resource('brands', AdminBrandController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
    Route::resource('variants', AdminProductVariantController::class)->only(['index', 'create', 'store', 'destroy']);
});
