<?php

use App\Modules\Admin\Presentation\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin,sales_admin'])->get('/admin', function () {
    return auth()->user()->role === 'super_admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('admin.orders.index');
})->name('admin.home');

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users.index');
    Route::post('/users/{id}/role', [SuperAdminController::class, 'updateUserRole'])->name('users.role');
    Route::get('/reports', [SuperAdminController::class, 'reports'])->name('reports');
    Route::get('/plugins', [SuperAdminController::class, 'plugins'])->name('plugins');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/settings/export', [SuperAdminController::class, 'exportSettings'])->name('settings.export');
    Route::post('/settings/import', [SuperAdminController::class, 'importSettings'])->name('settings.import');
});
