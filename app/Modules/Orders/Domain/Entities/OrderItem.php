<?php

namespace App\Modules\Orders\Domain\Entities;

class OrderItem
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly ?int $productVariantId,
        public readonly int $quantity,
        public readonly float $unitPrice,
        public readonly float $total
    ) {}
}
