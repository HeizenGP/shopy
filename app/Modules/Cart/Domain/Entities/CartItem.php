<?php

namespace App\Modules\Cart\Domain\Entities;

class CartItem
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly ?int $productVariantId,
        public readonly int $quantity
    ) {}
}
