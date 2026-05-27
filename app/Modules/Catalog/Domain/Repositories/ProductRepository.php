<?php

namespace App\Modules\Catalog\Domain\Repositories;

use App\Modules\Catalog\Domain\Entities\Product;

interface ProductRepository
{
    public function findById(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function save(Product $product): Product;
    /**
     * @return Product[]
     */
    public function findAllPublished(): array;
}
