<?php

use App\Modules\Auth\Presentation\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'webLogin']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'webLogout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('auth.dashboard');
    })->name('dashboard');
});
