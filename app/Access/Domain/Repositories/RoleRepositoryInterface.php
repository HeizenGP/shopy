<?php

namespace App\Access\Domain\Repositories;

use App\Access\Application\DTOs\CreateRoleData;
use App\Access\Application\DTOs\UpdateRoleData;
use App\Access\Infrastructure\Models\RoleModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function all(): Collection;

    public function find(int $id): ?RoleModel;

    public function create(CreateRoleData $data): RoleModel;

    public function update(int $id, UpdateRoleData $data): RoleModel;

    public function delete(int $id): void;

    public function syncPermissions(int $roleId, array $permissionIds): void;

    public function count(): int;
}
