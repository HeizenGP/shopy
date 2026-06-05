<?php

namespace App\Access\Infrastructure\Repositories;

use App\Access\Application\DTOs\CreateRoleData;
use App\Access\Application\DTOs\UpdateRoleData;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\RoleModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return RoleModel::query()
            ->withCount(['users', 'permissions'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function all(): Collection
    {
        return RoleModel::query()->orderByDesc('is_system')->orderBy('name')->get();
    }

    public function find(int $id): ?RoleModel
    {
        return RoleModel::query()->with('permissions')->find($id);
    }

    public function create(CreateRoleData $data): RoleModel
    {
        return DB::transaction(function () use ($data): RoleModel {
            $role = RoleModel::query()->create([
                'name' => $data->name,
                'slug' => $data->slug,
                'description' => $data->description,
                'is_system' => false,
            ]);

            $role->permissions()->sync($data->permissionIds);

            return $role->fresh('permissions');
        });
    }

    public function update(int $id, UpdateRoleData $data): RoleModel
    {
        return DB::transaction(function () use ($id, $data): RoleModel {
            $role = RoleModel::query()->findOrFail($id);
            $role->update([
                'name' => $data->name,
                'slug' => $data->slug,
                'description' => $data->description,
            ]);
            $role->permissions()->sync($data->permissionIds);

            return $role->fresh('permissions');
        });
    }

    public function delete(int $id): void
    {
        RoleModel::query()->findOrFail($id)->delete();
    }

    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        RoleModel::query()->findOrFail($roleId)->permissions()->sync($permissionIds);
    }

    public function count(): int
    {
        return RoleModel::query()->count();
    }
}
