<?php

use App\Modules\Admin\Presentation\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:admin.access'])->get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->name('admin.home');

Route::middleware(['auth', 'permission:admin.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->middleware('permission:admin.dashboard.view')->name('dashboard');
    Route::get('/users', [SuperAdminController::class, 'users'])->middleware('permission:users.manage')->name('users.index');
    Route::get('/users/administrativos', [SuperAdminController::class, 'administrativeUsers'])->middleware('permission:users.manage')->name('users.admin.index');
    Route::get('/users/web', [SuperAdminController::class, 'webUsers'])->middleware('permission:users.manage')->name('users.web.index');
    Route::post('/users/{id}/role', [SuperAdminController::class, 'updateUserRole'])->middleware('permission:users.manage')->name('users.role');
    Route::get('/roles', [SuperAdminController::class, 'roles'])->middleware('permission:roles.manage')->name('roles.index');
    Route::post('/roles', [SuperAdminController::class, 'storeRole'])->middleware('permission:roles.manage')->name('roles.store');
    Route::put('/roles/{id}', [SuperAdminController::class, 'updateRole'])->middleware('permission:roles.manage')->name('roles.update');
    Route::delete('/roles/{id}', [SuperAdminController::class, 'deleteRole'])->middleware('permission:roles.manage')->name('roles.destroy');
    Route::get('/reports', [SuperAdminController::class, 'reports'])->middleware('permission:reports.view')->name('reports');
    Route::get('/plugins', [SuperAdminController::class, 'plugins'])->middleware('permission:plugins.manage')->name('plugins');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->middleware('permission:settings.manage')->name('settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->middleware('permission:settings.manage')->name('settings.update');
    Route::get('/settings/export', [SuperAdminController::class, 'exportSettings'])->middleware('permission:settings.manage')->name('settings.export');
    Route::post('/settings/import', [SuperAdminController::class, 'importSettings'])->middleware('permission:settings.manage')->name('settings.import');
});
