<?php

namespace App\Catalog\Domain\Repositories;

use App\Catalog\Application\DTOs\CreateProductData;
use App\Catalog\Application\DTOs\UpdateProductData;
use App\Catalog\Infrastructure\Models\ProductModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 12): LengthAwarePaginator;

    public function published(int $perPage = 12): LengthAwarePaginator;

    public function featured(int $limit = 4): Collection;

    public function find(int $id): ?ProductModel;

    public function findPublishedBySlug(string $slug): ?ProductModel;

    public function create(CreateProductData $data): ProductModel;

    public function update(int $id, UpdateProductData $data): ProductModel;

    public function delete(int $id): void;
}
