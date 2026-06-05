<?php

namespace App\Access\Infrastructure\Database\Seeders;

use App\Access\Infrastructure\Models\PermissionModel;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = $this->permissions();

        foreach ($permissions as $permission) {
            PermissionModel::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        PermissionModel::query()
            ->whereNotIn('slug', array_column($permissions, 'slug'))
            ->delete();
    }

    private function permissions(): array
    {
        return [
            ['name' => 'Ver dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard', 'description' => 'Acceder al resumen principal del panel administrativo.'],
            ['name' => 'Gestionar usuarios', 'slug' => 'access.manage_users', 'module' => 'access', 'description' => 'Administrar el submódulo de usuarios.'],
            ['name' => 'Gestionar roles', 'slug' => 'access.manage_roles', 'module' => 'access', 'description' => 'Administrar el submódulo de roles.'],
            ['name' => 'Gestionar permisos', 'slug' => 'access.manage_permissions', 'module' => 'access', 'description' => 'Administrar el submódulo de permisos.'],
            ['name' => 'Gestionar productos', 'slug' => 'catalog.manage_products', 'module' => 'catalog', 'description' => 'Administrar productos del catálogo.'],
            ['name' => 'Gestionar categorías', 'slug' => 'catalog.manage_categories', 'module' => 'catalog', 'description' => 'Administrar categorías del catálogo.'],
            ['name' => 'Gestionar marcas', 'slug' => 'catalog.manage_brands', 'module' => 'catalog', 'description' => 'Administrar marcas del catálogo.'],
            ['name' => 'Gestionar variantes', 'slug' => 'catalog.manage_variants', 'module' => 'catalog', 'description' => 'Administrar variantes del catálogo.'],
        ];
    }

}
