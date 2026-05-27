<?php

namespace App\Modules\Cart\Domain\Entities;

use InvalidArgumentException;

class CartItem
{
    public function __construct(
        private ?int $id,
        private int $cartId,
        private int $productId,
        private ?int $productVariantId,
        private int $quantity
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductVariantId(): ?int
    {
        return $this->productVariantId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function changeQuantity(int $newQuantity): void
    {
        if ($newQuantity <= 0) {
            throw new InvalidArgumentException("La cantidad debe ser mayor a cero.");
        }
        $this->quantity = $newQuantity;
    }
}
