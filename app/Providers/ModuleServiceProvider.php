<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Ports (Domain Interfaces) to Adapters (Infrastructure Implementations)
        
        // Auth / Users
        $this->app->bind(
            \App\Modules\Auth\Domain\Repositories\UserRepositoryInterface::class,
            \App\Modules\Auth\Infrastructure\Adapters\EloquentUserRepository::class
        );

        // Catalog
        $this->app->bind(
            \App\Modules\Catalog\Domain\Repositories\ProductRepositoryInterface::class,
            \App\Modules\Catalog\Infrastructure\Adapters\EloquentProductRepository::class
        );

        // Cart
        $this->app->bind(
            \App\Modules\Cart\Domain\Repositories\CartRepositoryInterface::class,
            \App\Modules\Cart\Infrastructure\Adapters\EloquentCartRepository::class
        );

        // Coupons
        $this->app->bind(
            \App\Modules\Coupons\Domain\Repositories\CouponRepositoryInterface::class,
            \App\Modules\Coupons\Infrastructure\Adapters\EloquentCouponRepository::class
        );

        // Inventory
        $this->app->bind(
            \App\Modules\Inventory\Domain\Repositories\InventoryRepositoryInterface::class,
            \App\Modules\Inventory\Infrastructure\Adapters\EloquentInventoryRepository::class
        );

        // Reviews
        $this->app->bind(
            \App\Modules\Reviews\Domain\Repositories\ReviewRepositoryInterface::class,
            \App\Modules\Reviews\Infrastructure\Adapters\EloquentReviewRepository::class
        );

        // Orders
        $this->app->bind(
            \App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface::class,
            \App\Modules\Orders\Infrastructure\Adapters\EloquentOrderRepository::class
        );
    }

    public function boot(): void
    {
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(function () {
                // Auth Routes
                $this->loadRoutesFrom(app_path('Modules/Auth/Presentation/Routes/auth_routes.php'));
                
                // Catalog Routes
                $this->loadRoutesFrom(app_path('Modules/Catalog/Presentation/Routes/catalog_routes.php'));

                // Cart Routes
                $this->loadRoutesFrom(app_path('Modules/Cart/Presentation/Routes/cart_routes.php'));

                // Coupons Routes
                $this->loadRoutesFrom(app_path('Modules/Coupons/Presentation/Routes/coupon_routes.php'));

                // Inventory Routes
                $this->loadRoutesFrom(app_path('Modules/Inventory/Presentation/Routes/inventory_routes.php'));

                // Reviews Routes
                $this->loadRoutesFrom(app_path('Modules/Reviews/Presentation/Routes/review_routes.php'));

                // Orders Routes
                $this->loadRoutesFrom(app_path('Modules/Orders/Presentation/Routes/order_routes.php'));
            });
    }
}
