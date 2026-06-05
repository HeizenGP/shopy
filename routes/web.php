<?php

use App\Access\Presentation\Controllers\AdminPermissionController;
use App\Access\Presentation\Controllers\AdminRoleController;
use App\Access\Presentation\Controllers\AdminUserController;
use App\Access\Presentation\Controllers\AuthController;
use App\Access\Presentation\Controllers\DashboardController;
use App\Access\Presentation\Support\AdminNavigation;
use App\Catalog\Presentation\Controllers\AdminBrandController;
use App\Catalog\Presentation\Controllers\AdminCategoryController;
use App\Catalog\Presentation\Controllers\AdminProductController;
use App\Catalog\Presentation\Controllers\AdminProductVariantController;
use App\Catalog\Presentation\Controllers\PublicCategoryController;
use App\Catalog\Presentation\Controllers\PublicProductController;
use Illuminate\Http\Request;
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
        Route::get('/', function (Request $request) {
            return redirect()->route(AdminNavigation::firstRouteNameFor($request->user()) ?? 'admin.no-permissions');
        })->name('home');
        Route::get('/no-permissions', fn () => view('access.admin.no-permissions'))->name('no-permissions');
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.view')
            ->name('dashboard');

        Route::prefix('access')
            ->name('access.')
            ->group(function (): void {
                Route::get('users', [AdminUserController::class, 'index'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.index');
                Route::get('users/create', [AdminUserController::class, 'create'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.create');
                Route::post('users', [AdminUserController::class, 'store'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.store');
                Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.edit');
                Route::match(['put', 'patch'], 'users/{user}', [AdminUserController::class, 'update'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.update');
                Route::delete('users/{user}', [AdminUserController::class, 'destroy'])
                    ->middleware('permission:access.manage_users')
                    ->name('users.destroy');

                Route::get('roles', [AdminRoleController::class, 'index'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.index');
                Route::get('roles/create', [AdminRoleController::class, 'create'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.create');
                Route::post('roles', [AdminRoleController::class, 'store'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.store');
                Route::get('roles/{role}/edit', [AdminRoleController::class, 'edit'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.edit');
                Route::match(['put', 'patch'], 'roles/{role}', [AdminRoleController::class, 'update'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.update');
                Route::delete('roles/{role}', [AdminRoleController::class, 'destroy'])
                    ->middleware('permission:access.manage_roles')
                    ->name('roles.destroy');

                Route::get('permissions', [AdminPermissionController::class, 'index'])
                    ->middleware('permission:access.manage_permissions')
                    ->name('permissions.index');
            });

        Route::prefix('catalog')
            ->name('catalog.')
            ->group(function (): void {
                Route::get('products', [AdminProductController::class, 'index'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.index');
                Route::get('products/create', [AdminProductController::class, 'create'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.create');
                Route::post('products', [AdminProductController::class, 'store'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.store');
                Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.edit');
                Route::match(['put', 'patch'], 'products/{product}', [AdminProductController::class, 'update'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.update');
                Route::delete('products/{product}', [AdminProductController::class, 'destroy'])
                    ->middleware('permission:catalog.manage_products')
                    ->name('products.destroy');

                Route::resource('categories', AdminCategoryController::class)
                    ->only(['index', 'create', 'store', 'update', 'destroy'])
                    ->middleware('permission:catalog.manage_categories');
                Route::resource('brands', AdminBrandController::class)
                    ->only(['index', 'create', 'store', 'update', 'destroy'])
                    ->middleware('permission:catalog.manage_brands');
                Route::resource('variants', AdminProductVariantController::class)
                    ->only(['index', 'create', 'store', 'destroy'])
                    ->middleware('permission:catalog.manage_variants');
            });
    });
