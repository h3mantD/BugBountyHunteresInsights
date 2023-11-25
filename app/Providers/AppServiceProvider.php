<?php

declare(strict_types=1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // code
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Loader Alias
        $loader = AliasLoader::getInstance();

        // SANCTUM CUSTOM PERSONAL-ACCESS-TOKEN
        $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\Sanctum\PersonalAccessToken::class);
        $loader->alias(\Ichtrojan\Otp\Models\Otp::class, \App\Models\Otp::class);

        Scramble::extendOpenApi(function (OpenApi $openApi): void {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });
    }
}
