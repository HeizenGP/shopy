<?php

namespace App\Access\Application\UseCases;

use App\Access\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListUsersUseCase
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function forAdmin(array $filters = []): LengthAwarePaginator
    {
        return $this->users->paginateForAdmin(filters: $filters);
    }
}
