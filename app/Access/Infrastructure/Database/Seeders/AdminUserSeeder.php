<?php

namespace App\Access\Infrastructure\Database\Seeders;

use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ACCESS_ADMIN_NAME') ?: 'Super Admin';
        $email = env('ACCESS_ADMIN_EMAIL') ?: 'admin@shopy.test';
        $password = env('ACCESS_ADMIN_PASSWORD') ?: 'AdminShopy2026!';

        $user = UserModel::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_active' => true,
                'password_changed_at' => now(),
            ]
        );

        $role = RoleModel::query()->where('slug', 'super_admin')->first();

        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
