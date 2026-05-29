<?php

namespace App\Catalog\Application\UseCases;

use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListProductsUseCase
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function forAdmin(int $perPage = 12): LengthAwarePaginator
    {
        return $this->products->paginateForAdmin($perPage);
    }

    public function publicCatalog(int $perPage = 12): LengthAwarePaginator
    {
        return $this->products->published($perPage);
    }
}
