<?php

namespace App\Modules\Catalog\Application\UseCases;

use App\Modules\Catalog\Application\DTOs\ProductResponseDTO;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;

class ListProductsUseCase
{
    public function __construct(private ProductRepository $repository) {}

    /**
     * @return ProductResponseDTO[]
     */
    public function execute(): array
    {
        $products = $this->repository->findAllPublished();

        return array_map(
            fn ($product) => ProductResponseDTO::fromEntity($product),
            $products
        );
    }
}
