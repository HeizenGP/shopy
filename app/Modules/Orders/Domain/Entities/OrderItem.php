<?php

namespace App\Modules\Orders\Domain\Entities;

class OrderItem
{
    public function __construct(
        private ?int $id,
        private ?int $orderId,
        private ?int $productId,
        private ?int $productVariantId,
        private string $name,
        private int $quantity,
        private float $price
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getProductVariantId(): ?int
    {
        return $this->productVariantId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSubtotal(): float
    {
        return round($this->quantity * $this->price, 2);
    }
}
