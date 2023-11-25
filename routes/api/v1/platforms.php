<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlatformController::class, 'all'])->name('all');
Route::post('/add', [PlatformController::class, 'add'])->name('add');
Route::post('{platform}/validate', [PlatformController::class, 'validateOtp'])->name('validate-otp');
Route::post('/update-stats', [PlatformController::class, 'updateStats'])->name('update-stats');
Route::post('/delete', [PlatformController::class, 'delete'])->name('delete');
