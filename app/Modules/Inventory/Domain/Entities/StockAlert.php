<?php

namespace App\Modules\Inventory\Domain\Entities;

class StockAlert
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly ?int $productVariantId,
        public readonly string $message,
        public readonly bool $isResolved
    ) {}
}
