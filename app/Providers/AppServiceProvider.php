<?php

namespace App\Providers;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\PermissionRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Repositories\EloquentAuditLogRepository;
use App\Access\Infrastructure\Repositories\EloquentPermissionRepository;
use App\Access\Infrastructure\Repositories\EloquentRoleRepository;
use App\Access\Infrastructure\Repositories\EloquentUserRepository;
use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloquentPermissionRepository::class);
        $this->app->bind(AuditLogRepositoryInterface::class, EloquentAuditLogRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
