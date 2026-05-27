<?php

namespace App\Modules\Catalog\Domain\Repositories;

use App\Modules\Catalog\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function findAll(array $filters = []): array;
    
    public function findById(int $id): ?Product;
    
    public function findBySlug(string $slug): ?Product;

    public function findVariantById(int $variantId): ?\App\Modules\Catalog\Domain\Entities\Variant;

    public function save(Product $product): Product;

    public function delete(int $id): bool;
}
