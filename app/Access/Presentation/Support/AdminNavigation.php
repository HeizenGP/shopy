<?php

namespace App\Access\Presentation\Support;

use App\Access\Infrastructure\Models\UserModel;

final class AdminNavigation
{
    public static function firstRouteNameFor(?UserModel $user): ?string
    {
        if (! $user) {
            return null;
        }

        foreach (self::landingRoutes() as $permission => $routeName) {
            if ($user->hasPermission($permission)) {
                return $routeName;
            }
        }

        return null;
    }

    public static function landingRoutes(): array
    {
        return [
            'dashboard.view' => 'admin.dashboard',
            'access.manage_users' => 'admin.access.users.index',
            'access.manage_roles' => 'admin.access.roles.index',
            'access.manage_permissions' => 'admin.access.permissions.index',
            'catalog.manage_products' => 'admin.catalog.products.index',
            'catalog.manage_categories' => 'admin.catalog.categories.index',
            'catalog.manage_brands' => 'admin.catalog.brands.index',
            'catalog.manage_variants' => 'admin.catalog.variants.index',
        ];
    }
}
