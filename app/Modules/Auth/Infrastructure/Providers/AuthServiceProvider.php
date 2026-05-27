<?php

namespace App\Modules\Auth\Infrastructure\Providers;

use App\Modules\Auth\Application\Ports\AuthSessionManager;
use App\Modules\Auth\Domain\Repositories\AuthUserRepository;
use App\Modules\Auth\Domain\Services\PasswordHasher;
use App\Modules\Auth\Infrastructure\Adapters\EloquentAuthUserRepository;
use App\Modules\Auth\Infrastructure\Adapters\LaravelPasswordHasher;
use App\Modules\Auth\Infrastructure\Adapters\LaravelSessionAuthManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthUserRepository::class, EloquentAuthUserRepository::class);
        $this->app->bind(PasswordHasher::class, LaravelPasswordHasher::class);
        $this->app->bind(AuthSessionManager::class, LaravelSessionAuthManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/api.php');
            });

        Route::middleware('web')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/web.php');
            });
    }
}
