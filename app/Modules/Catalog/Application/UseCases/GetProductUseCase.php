<?php

namespace App\Modules\Catalog\Application\UseCases;

use App\Modules\Catalog\Application\DTOs\ProductResponseDTO;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;
use DomainException;

class GetProductUseCase
{
    public function __construct(private ProductRepository $repository) {}

    public function execute(string $slug): ProductResponseDTO
    {
        $product = $this->repository->findBySlug($slug);

        if (!$product || $product->getStatus() !== 'published') {
            throw new DomainException("Producto no encontrado.");
        }

        return ProductResponseDTO::fromEntity($product);
    }
}
