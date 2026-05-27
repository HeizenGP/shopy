<?php

namespace App\Modules\Payments\Infrastructure\Providers;

use App\Modules\Payments\Domain\Repositories\PaymentRepository;
use App\Modules\Payments\Domain\Services\PaymentGateway;
use App\Modules\Payments\Infrastructure\Adapters\EloquentPaymentRepository;
use App\Modules\Payments\Infrastructure\Adapters\MockPaymentGateway;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentRepository::class, EloquentPaymentRepository::class);
        $this->app->bind(PaymentGateway::class, MockPaymentGateway::class);
    }

    public function boot(): void
    {
        Route::prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/api.php');
            });
    }
}
