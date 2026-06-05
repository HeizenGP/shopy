<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListRolesUseCase
{
    public function __construct(private RoleRepositoryInterface $roles) {}

    public function forAdmin(array $filters = []): LengthAwarePaginator
    {
        return $this->roles->paginateForAdmin(filters: $filters);
    }
}
