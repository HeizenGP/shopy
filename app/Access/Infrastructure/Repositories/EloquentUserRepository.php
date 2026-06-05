<?php

namespace App\Access\Infrastructure\Repositories;

use App\Access\Application\DTOs\CreateUserData;
use App\Access\Application\DTOs\UpdateUserData;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return UserModel::query()
            ->with('roles')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(array_key_exists('is_active', $filters) && $filters['is_active'] !== null, fn ($query) => $query->where('is_active', (bool) $filters['is_active']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?UserModel
    {
        return UserModel::query()->with('roles.permissions')->find($id);
    }

    public function findByEmail(string $email): ?UserModel
    {
        return UserModel::query()
            ->with('roles.permissions')
            ->where('email', mb_strtolower(trim($email)))
            ->first();
    }

    public function create(CreateUserData $data): UserModel
    {
        return DB::transaction(function () use ($data): UserModel {
            $user = UserModel::query()->create([
                'name' => $data->name,
                'email' => mb_strtolower(trim($data->email)),
                'password' => $data->password,
                'is_active' => $data->isActive,
                'password_changed_at' => now(),
            ]);

            $user->roles()->sync($data->roleIds);

            return $user->fresh('roles.permissions');
        });
    }

    public function update(int $id, UpdateUserData $data): UserModel
    {
        return DB::transaction(function () use ($id, $data): UserModel {
            $payload = [
                'name' => $data->name,
                'email' => mb_strtolower(trim($data->email)),
                'is_active' => $data->isActive,
            ];

            if ($data->password !== null && $data->password !== '') {
                $payload['password'] = $data->password;
                $payload['password_changed_at'] = now();
            }

            $user = UserModel::query()->findOrFail($id);
            $user->update($payload);
            $user->roles()->sync($data->roleIds);

            return $user->fresh('roles.permissions');
        });
    }

    public function delete(int $id): void
    {
        UserModel::query()->findOrFail($id)->delete();
    }

    public function syncRoles(int $userId, array $roleIds): void
    {
        UserModel::query()->findOrFail($userId)->roles()->sync($roleIds);
    }

    public function markLogin(int $userId, ?string $ipAddress): void
    {
        UserModel::query()->whereKey($userId)->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);
    }

    public function count(): int
    {
        return UserModel::query()->count();
    }

    public function activeCount(): int
    {
        return UserModel::query()->where('is_active', true)->count();
    }
}
