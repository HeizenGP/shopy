<?php

namespace App\Access\Infrastructure\Repositories;

use App\Access\Domain\Repositories\PermissionRepositoryInterface;
use App\Access\Infrastructure\Models\PermissionModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 30, array $filters = []): LengthAwarePaginator
    {
        return PermissionModel::query()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('module', 'like', "%{$search}%");
                });
            })
            ->when($filters['module'] ?? null, fn ($query, string $module) => $query->where('module', $module))
            ->orderBy('module')
            ->orderBy('slug')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function all(): Collection
    {
        return PermissionModel::query()->orderBy('module')->orderBy('slug')->get();
    }

    public function groupedByModule(): Collection
    {
        return $this->all()->groupBy('module');
    }

    public function count(): int
    {
        return PermissionModel::query()->count();
    }
}
