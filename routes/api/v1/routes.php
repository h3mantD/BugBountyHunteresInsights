<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('platforms')->as('platforms:')->group(base_path('routes/api/v1/platforms.php'));
