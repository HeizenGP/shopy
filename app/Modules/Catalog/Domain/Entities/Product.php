<?php

namespace App\Modules\Catalog\Domain\Entities;

class Product
{
    /**
     * @param Variant[] $variants
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly float $price,
        public readonly ?string $category,
        public readonly ?string $image,
        public readonly bool $isActive,
        public readonly array $variants = []
    ) {}
}
