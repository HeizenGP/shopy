<?php

namespace App\Modules\Catalog\Domain\Entities;

use App\Modules\Catalog\Domain\ValueObjects\Price;

class Product
{
    /**
     * @param ProductVariant[] $variants
     */
    public function __construct(
        private ?int $id,
        private string $name,
        private string $slug,
        private ?string $description,
        private Price $price,
        private string $status,
        private int $stock,
        private array $variants = []
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @return ProductVariant[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'published' && $this->stock > 0;
    }
}
