<?php

namespace App\Access\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 30, array $filters = []): LengthAwarePaginator;

    public function all(): Collection;

    public function groupedByModule(): Collection;

    public function count(): int;
}
