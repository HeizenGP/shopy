<?php

namespace App\Access\Domain\Services;

use App\Access\Domain\Entities\AccessUser;
use App\Access\Domain\Entities\Permission;
use App\Access\Domain\Entities\Role;

final class PermissionChecker
{
    public function userCan(AccessUser $user, string $permissionSlug): bool
    {
        if (! $user->isActive) {
            return false;
        }

        foreach ($user->roles as $role) {
            if ($this->roleSlug($role) === 'super_admin') {
                return true;
            }

            foreach ($this->permissions($role) as $permission) {
                if ($this->permissionSlug($permission) === $permissionSlug) {
                    return true;
                }
            }
        }

        return false;
    }

    private function roleSlug(Role|array $role): ?string
    {
        return $role instanceof Role ? $role->slug : ($role['slug'] ?? null);
    }

    private function permissions(Role|array $role): array
    {
        return $role instanceof Role ? $role->permissions : ($role['permissions'] ?? []);
    }

    private function permissionSlug(Permission|string|array $permission): ?string
    {
        if ($permission instanceof Permission) {
            return $permission->slug->value();
        }

        if (is_array($permission)) {
            return $permission['slug'] ?? null;
        }

        return $permission;
    }
}
