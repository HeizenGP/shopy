<?php

namespace App\Modules\Catalog\Application\DTOs;

use App\Modules\Catalog\Domain\Entities\Product;

class ProductResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly float $price,
        public readonly string $formattedPrice,
        public readonly int $stock,
        public readonly array $variants
    ) {}

    public static function fromEntity(Product $product): self
    {
        $variantsData = [];
        foreach ($product->getVariants() as $variant) {
            $variantsData[] = [
                'id' => $variant->getId(),
                'name' => $variant->getName(),
                'sku' => $variant->getSku(),
                'price' => $variant->getPrice() ? $variant->getPrice()->getAmount() : null,
                'formattedPrice' => $variant->getPrice() ? $variant->getPrice()->format() : null,
                'stock' => $variant->getStock(),
            ];
        }

        return new self(
            id: $product->getId(),
            name: $product->getName(),
            slug: $product->getSlug(),
            description: $product->getDescription(),
            price: $product->getPrice()->getAmount(),
            formattedPrice: $product->getPrice()->format(),
            stock: $product->getStock(),
            variants: $variantsData
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => $this->formattedPrice,
            'stock' => $this->stock,
            'variants' => $this->variants,
        ];
    }
}
