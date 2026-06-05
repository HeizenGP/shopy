<?php

use App\Access\Presentation\Controllers\AdminPermissionController;
use App\Access\Presentation\Controllers\AdminRoleController;
use App\Access\Presentation\Controllers\AdminUserController;
use App\Access\Presentation\Controllers\AuthController;
use App\Access\Presentation\Controllers\DashboardController;
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

Route::get('/cart', function () {
    return view('catalog.public.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('catalog.public.checkout');
})->name('checkout');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('access')
            ->name('access.')
            ->middleware('permission:access.view')
            ->group(function (): void {
                Route::get('users', [AdminUserController::class, 'index'])
                    ->middleware('permission:users.view')
                    ->name('users.index');
                Route::get('users/create', [AdminUserController::class, 'create'])
                    ->middleware('permission:users.create')
                    ->name('users.create');
                Route::post('users', [AdminUserController::class, 'store'])
                    ->middleware('permission:users.create')
                    ->name('users.store');
                Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])
                    ->middleware('permission:users.update')
                    ->name('users.edit');
                Route::match(['put', 'patch'], 'users/{user}', [AdminUserController::class, 'update'])
                    ->middleware('permission:users.update')
                    ->name('users.update');
                Route::delete('users/{user}', [AdminUserController::class, 'destroy'])
                    ->middleware('permission:users.delete')
                    ->name('users.destroy');

                Route::get('roles', [AdminRoleController::class, 'index'])
                    ->middleware('permission:roles.view')
                    ->name('roles.index');
                Route::get('roles/create', [AdminRoleController::class, 'create'])
                    ->middleware('permission:roles.create')
                    ->name('roles.create');
                Route::post('roles', [AdminRoleController::class, 'store'])
                    ->middleware('permission:roles.create')
                    ->name('roles.store');
                Route::get('roles/{role}/edit', [AdminRoleController::class, 'edit'])
                    ->middleware('permission:roles.update')
                    ->name('roles.edit');
                Route::match(['put', 'patch'], 'roles/{role}', [AdminRoleController::class, 'update'])
                    ->middleware('permission:roles.update')
                    ->name('roles.update');
                Route::delete('roles/{role}', [AdminRoleController::class, 'destroy'])
                    ->middleware('permission:roles.delete')
                    ->name('roles.destroy');

                Route::get('permissions', [AdminPermissionController::class, 'index'])
                    ->middleware('permission:permissions.view')
                    ->name('permissions.index');
            });
    });

Route::prefix('admin/catalog')->name('admin.catalog.')->group(function (): void {
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
    Route::resource('brands', AdminBrandController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
    Route::resource('variants', AdminProductVariantController::class)->only(['index', 'create', 'store', 'destroy']);
});
