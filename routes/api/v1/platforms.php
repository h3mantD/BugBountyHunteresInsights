<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlatformController::class, 'all'])->name('all');
Route::post('/add', [PlatformController::class, 'add'])->name('add');
Route::post('/delete', [PlatformController::class, 'delete'])->name('delete');
Route::post('{platform}/edit', [PlatformController::class, 'edit'])->name('edit');
Route::post('{platform}/validate', [PlatformController::class, 'validateOtp'])->name('validateOtp');
