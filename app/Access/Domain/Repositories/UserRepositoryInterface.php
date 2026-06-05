<?php

namespace App\Access\Domain\Repositories;

use App\Access\Application\DTOs\CreateUserData;
use App\Access\Application\DTOs\UpdateUserData;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function find(int $id): ?UserModel;

    public function findByEmail(string $email): ?UserModel;

    public function create(CreateUserData $data): UserModel;

    public function update(int $id, UpdateUserData $data): UserModel;

    public function delete(int $id): void;

    public function syncRoles(int $userId, array $roleIds): void;

    public function markLogin(int $userId, ?string $ipAddress): void;

    public function count(): int;

    public function activeCount(): int;
}
