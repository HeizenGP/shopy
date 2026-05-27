<?php

namespace App\Modules\Cart\Domain\Entities;

class Cart
{
    /**
     * @param CartItem[] $items
     */
    public function __construct(
        private ?int $id,
        private ?int $userId,
        private ?string $sessionId,
        private array $items = []
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(int $productId, ?int $productVariantId, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId && $item->getProductVariantId() === $productVariantId) {
                $item->changeQuantity($item->getQuantity() + $quantity);
                return;
            }
        }

        $this->items[] = new CartItem(
            id: null,
            cartId: $this->id ?? 0,
            productId: $productId,
            productVariantId: $productVariantId,
            quantity: $quantity
        );
    }
}
