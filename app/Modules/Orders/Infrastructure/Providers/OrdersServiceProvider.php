<?php

namespace App\Modules\Orders\Infrastructure\Providers;

use App\Modules\Orders\Domain\Repositories\OrderRepository;
use App\Modules\Orders\Infrastructure\Adapters\EloquentOrderRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepository::class, EloquentOrderRepository::class);
    }

    public function boot(): void
    {
        Route::prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/api.php');
            });
    }
}
