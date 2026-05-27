<?php

namespace App\Modules\Catalog\Application\UseCases;

use App\Modules\Catalog\Domain\Entities\Product;
use App\Modules\Catalog\Domain\Repositories\ProductRepositoryInterface;
use Exception;

class GetProductDetailsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function execute(string $slug): Product
    {
        $product = $this->productRepository->findBySlug($slug);
        if (!$product) {
            throw new Exception('Producto no encontrado.');
        }
        return $product;
    }
}
