<?php

namespace App\Modules\Inventory\Domain\Entities;

class Inventory
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly ?int $productVariantId,
        public readonly int $stock,
        public readonly int $lowStockThreshold
    ) {}

    public function isLowStock(): bool
    {
        return $this->stock <= $this->lowStockThreshold;
    }
}
