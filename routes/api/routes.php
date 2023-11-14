<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::prefix('auth')->as('auth:')->group(base_path('routes/api/auth.php'));

Route::middleware('auth:sanctum')->prefix('v1')->as('v1:')->group(base_path('routes/api/v1/routes.php'));
