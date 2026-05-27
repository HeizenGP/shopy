<?php

namespace App\Modules\Catalog\Application\UseCases;

use App\Modules\Catalog\Domain\Repositories\ProductRepositoryInterface;

class ListCatalogUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function execute(array $filters = []): array
    {
        // By default on front catalog, only fetch active products
        $filters['active_only'] = $filters['active_only'] ?? true;
        return $this->productRepository->findAll($filters);
    }
}
