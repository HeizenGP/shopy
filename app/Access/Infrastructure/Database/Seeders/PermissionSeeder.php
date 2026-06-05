<?php

namespace App\Access\Infrastructure\Database\Seeders;

use App\Access\Infrastructure\Models\PermissionModel;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->permissions() as $permission) {
            PermissionModel::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }

    private function permissions(): array
    {
        return [
            ['name' => 'Ver Access', 'slug' => 'access.view', 'module' => 'access', 'description' => 'Acceso al módulo Access.'],
            ['name' => 'Ver usuarios', 'slug' => 'users.view', 'module' => 'users', 'description' => 'Listar usuarios administrativos.'],
            ['name' => 'Crear usuarios', 'slug' => 'users.create', 'module' => 'users', 'description' => 'Crear usuarios administrativos.'],
            ['name' => 'Actualizar usuarios', 'slug' => 'users.update', 'module' => 'users', 'description' => 'Editar usuarios administrativos.'],
            ['name' => 'Eliminar usuarios', 'slug' => 'users.delete', 'module' => 'users', 'description' => 'Eliminar usuarios administrativos.'],
            ['name' => 'Ver roles', 'slug' => 'roles.view', 'module' => 'roles', 'description' => 'Listar roles.'],
            ['name' => 'Crear roles', 'slug' => 'roles.create', 'module' => 'roles', 'description' => 'Crear roles.'],
            ['name' => 'Actualizar roles', 'slug' => 'roles.update', 'module' => 'roles', 'description' => 'Editar roles.'],
            ['name' => 'Eliminar roles', 'slug' => 'roles.delete', 'module' => 'roles', 'description' => 'Eliminar roles no protegidos.'],
            ['name' => 'Asignar permisos a roles', 'slug' => 'roles.assign_permissions', 'module' => 'roles', 'description' => 'Administrar permisos de roles.'],
            ['name' => 'Ver permisos', 'slug' => 'permissions.view', 'module' => 'permissions', 'description' => 'Consultar permisos disponibles.'],
            ['name' => 'Ver catálogo', 'slug' => 'catalog.view', 'module' => 'catalog', 'description' => 'Acceder al catálogo.'],
            ['name' => 'Crear catálogo', 'slug' => 'catalog.create', 'module' => 'catalog', 'description' => 'Crear recursos de catálogo.'],
            ['name' => 'Actualizar catálogo', 'slug' => 'catalog.update', 'module' => 'catalog', 'description' => 'Editar recursos de catálogo.'],
            ['name' => 'Eliminar catálogo', 'slug' => 'catalog.delete', 'module' => 'catalog', 'description' => 'Eliminar recursos de catálogo.'],
            ['name' => 'Gestionar categorías', 'slug' => 'catalog.manage_categories', 'module' => 'catalog', 'description' => 'Administrar categorías.'],
            ['name' => 'Gestionar marcas', 'slug' => 'catalog.manage_brands', 'module' => 'catalog', 'description' => 'Administrar marcas.'],
            ['name' => 'Gestionar variantes', 'slug' => 'catalog.manage_variants', 'module' => 'catalog', 'description' => 'Administrar variantes.'],
            ['name' => 'Gestionar imágenes', 'slug' => 'catalog.manage_images', 'module' => 'catalog', 'description' => 'Administrar imágenes.'],
        ];
    }
}
