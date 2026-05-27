<?php

namespace App\Modules\Catalog\Domain\Entities;

class VariantOption
{
    public function __construct(
        public readonly int $id,
        public readonly int $variantId,
        public readonly string $name,
        public readonly string $value
    ) {}
}
