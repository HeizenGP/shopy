<?php

use App\Modules\Users\Presentation\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users/register', [UserController::class, 'register']);
Route::get('/users/profile', [UserController::class, 'profile'])->middleware('auth');
