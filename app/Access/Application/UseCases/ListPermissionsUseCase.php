<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class ListPermissionsUseCase
{
    public function __construct(private PermissionRepositoryInterface $permissions) {}

    public function forAdmin(array $filters = []): LengthAwarePaginator
    {
        return $this->permissions->paginateForAdmin(filters: $filters);
    }

    public function groupedByModule(): Collection
    {
        return $this->permissions->groupedByModule();
    }
}
