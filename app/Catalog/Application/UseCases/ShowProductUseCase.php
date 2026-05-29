<?php

namespace App\Catalog\Application\UseCases;

use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Models\ProductModel;

final readonly class ShowProductUseCase
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function byId(int $id): ?ProductModel
    {
        return $this->products->find($id);
    }

    public function publishedBySlug(string $slug): ?ProductModel
    {
        return $this->products->findPublishedBySlug($slug);
    }
}
