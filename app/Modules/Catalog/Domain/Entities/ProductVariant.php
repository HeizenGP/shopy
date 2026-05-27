<?php

namespace App\Modules\Catalog\Domain\Entities;

use App\Modules\Catalog\Domain\ValueObjects\Price;

class ProductVariant
{
    public function __construct(
        private ?int $id,
        private int $productId,
        private string $name,
        private string $sku,
        private ?Price $price,
        private int $stock
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
