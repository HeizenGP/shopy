<?php

namespace App\Modules\Cart\Infrastructure\Providers;

use App\Modules\Cart\Domain\Repositories\CartRepository;
use App\Modules\Cart\Infrastructure\Adapters\EloquentCartRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartRepository::class, EloquentCartRepository::class);
    }

    public function boot(): void
    {
        Route::prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/api.php');
            });
    }
}
