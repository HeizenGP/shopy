<?php

namespace App\Modules\Auth\Infrastructure\Database\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class UserEloquent extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles, true);
    }

    public function roleRecord(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'key');
    }

    public function hasPermission(string|array $permissions): bool
    {
        $permissions = (array) $permissions;

        if ($this->role === 'super_admin') {
            return true;
        }

        $role = $this->roleRecord()->with('permissions')->first();

        if (!$role) {
            return false;
        }

        $allowed = $role->permissions->pluck('key')->all();

        return count(array_intersect($permissions, $allowed)) > 0;
    }

    public function permissions(): Collection
    {
        return $this->roleRecord()->first()?->permissions ?? collect();
    }
}

