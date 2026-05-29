<?php

namespace App\Catalog\Domain\Entities;

use App\Catalog\Domain\ValueObjects\Money;
use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Domain\ValueObjects\Sku;

final readonly class Product
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $slug,
        public Sku $sku,
        public Money $regularPrice,
        public ?Money $salePrice,
        public ProductStatus $status,
        public ?int $brandId = null,
        public ?int $mainCategoryId = null,
        public ?string $shortDescription = null,
        public ?string $description = null,
        public bool $isFeatured = false,
        public bool $hasVariants = false,
    ) {}

    public function displayPrice(): Money
    {
        return $this->salePrice ?? $this->regularPrice;
    }
}
