<?php

namespace App\Catalog\Application\UseCases;

use App\Catalog\Domain\Repositories\ProductRepositoryInterface;

final readonly class DeleteProductUseCase
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(int $id): void
    {
        $this->products->delete($id);
    }
}
