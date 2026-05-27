<?php

namespace App\Modules\Catalog\Domain\Entities;

class Variant
{
    /**
     * @param VariantOption[] $options
     */
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly string $sku,
        public readonly float $price,
        public readonly array $options = []
    ) {}
}
