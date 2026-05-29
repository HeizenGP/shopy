<?php

namespace App\Catalog\Application\DTOs;

use App\Catalog\Domain\ValueObjects\ProductStatus;

final readonly class UpdateProductData
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $sku,
        public float $regularPrice,
        public ?float $salePrice,
        public ProductStatus $status,
        public ?int $brandId,
        public ?int $mainCategoryId,
        public ?string $shortDescription,
        public ?string $description,
        public bool $isFeatured,
        public bool $hasVariants,
        public array $categoryIds = [],
        public array $variants = [],
    ) {}
}
