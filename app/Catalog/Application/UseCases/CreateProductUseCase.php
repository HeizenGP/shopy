<?php

namespace App\Catalog\Application\UseCases;

use App\Catalog\Application\DTOs\CreateProductData;
use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Models\ProductModel;

final readonly class CreateProductUseCase
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(CreateProductData $data): ProductModel
    {
        return $this->products->create($data);
    }
}
