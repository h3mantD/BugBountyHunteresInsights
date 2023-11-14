<?php

declare(strict_types=1);

use App\Http\Controllers\ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
