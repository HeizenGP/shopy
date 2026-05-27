<?php

namespace App\Modules\Coupons\Infrastructure\Providers;

use App\Modules\Coupons\Domain\Repositories\CouponRepository;
use App\Modules\Coupons\Infrastructure\Adapters\EloquentCouponRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CouponsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CouponRepository::class, EloquentCouponRepository::class);
    }

    public function boot(): void
    {
        Route::prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/api.php');
            });
    }
}
