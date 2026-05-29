<?php

namespace App\Catalog\Domain\Entities;

use App\Catalog\Domain\ValueObjects\Money;
use App\Catalog\Domain\ValueObjects\Sku;

final readonly class ProductVariant
{
    public function __construct(
        public ?int $id,
        public int $productId,
        public string $name,
        public Sku $sku,
        public ?Money $regularPrice = null,
        public ?Money $salePrice = null,
        public bool $isActive = true,
        public bool $isDefault = false,
    ) {}
}
