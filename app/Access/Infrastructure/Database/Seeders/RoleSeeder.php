<?php

namespace App\Access\Infrastructure\Database\Seeders;

use App\Access\Infrastructure\Models\PermissionModel;
use App\Access\Infrastructure\Models\RoleModel;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Acceso total al sistema.', 'is_system' => true],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administración general.', 'is_system' => true],
            ['name' => 'Catalog Manager', 'slug' => 'catalog_manager', 'description' => 'Gestión operativa del catálogo.', 'is_system' => true],
            ['name' => 'Inventory Manager', 'slug' => 'inventory_manager', 'description' => 'Gestión futura de inventario.', 'is_system' => true],
            ['name' => 'Order Manager', 'slug' => 'order_manager', 'description' => 'Gestión futura de pedidos.', 'is_system' => true],
        ];

        foreach ($roles as $roleData) {
            RoleModel::query()->updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        $allPermissions = PermissionModel::query()->pluck('id')->all();
        RoleModel::query()->where('slug', 'super_admin')->first()?->permissions()->sync($allPermissions);

        $adminPermissions = PermissionModel::query()
            ->whereIn('slug', [
                'access.view',
                'users.view',
                'users.create',
                'users.update',
                'roles.view',
                'permissions.view',
                'catalog.view',
                'catalog.create',
                'catalog.update',
                'catalog.delete',
                'catalog.manage_categories',
                'catalog.manage_brands',
                'catalog.manage_variants',
                'catalog.manage_images',
            ])
            ->pluck('id')
            ->all();
        RoleModel::query()->where('slug', 'admin')->first()?->permissions()->sync($adminPermissions);

        $catalogPermissions = PermissionModel::query()
            ->where('module', 'catalog')
            ->pluck('id')
            ->all();
        RoleModel::query()->where('slug', 'catalog_manager')->first()?->permissions()->sync($catalogPermissions);
    }
}
