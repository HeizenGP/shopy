<?php

namespace App\Access\Infrastructure\Database\Seeders;

use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = UserModel::query()->updateOrCreate(
            ['email' => 'heizen@shopy.test'],
            [
                'name' => 'Super Admin',
                'password' => 'heizen123',
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
