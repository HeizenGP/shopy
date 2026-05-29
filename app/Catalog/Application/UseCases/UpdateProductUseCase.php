<?php

namespace App\Catalog\Application\UseCases;

use App\Catalog\Application\DTOs\UpdateProductData;
use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Models\ProductModel;

final readonly class UpdateProductUseCase
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(int $id, UpdateProductData $data): ProductModel
    {
        return $this->products->update($id, $data);
    }
}
