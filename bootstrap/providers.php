<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    App\Modules\Auth\Infrastructure\Providers\AuthServiceProvider::class,
    App\Modules\Catalog\Infrastructure\Providers\CatalogServiceProvider::class,
    App\Modules\Cart\Infrastructure\Providers\CartServiceProvider::class,
    App\Modules\Coupons\Infrastructure\Providers\CouponsServiceProvider::class,
    App\Modules\Orders\Infrastructure\Providers\OrdersServiceProvider::class,
    App\Modules\Payments\Infrastructure\Providers\PaymentsServiceProvider::class,
    App\Modules\Users\Infrastructure\Providers\UsersServiceProvider::class,
];
