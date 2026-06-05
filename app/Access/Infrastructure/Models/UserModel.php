<?php

namespace App\Access\Infrastructure\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'is_active',
    'last_login_at',
    'last_login_ip',
    'password_changed_at',
])]
#[Hidden(['password', 'remember_token'])]
class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(RoleModel::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    public function hasRole(string $slug): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains('slug', $slug);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $this->loadMissing('roles.permissions');

        if ($this->roles->contains('slug', 'super_admin')) {
            return true;
        }

        return $this->roles->contains(
            fn (RoleModel $role): bool => $role->permissions->contains('slug', $permissionSlug)
        );
    }
}
