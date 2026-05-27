<?php

namespace App\Modules\Cart\Application\DTOs;

use App\Modules\Cart\Domain\Entities\Cart;

class CartResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId,
        public readonly ?string $sessionId,
        public readonly array $items
    ) {}

    public static function fromEntity(Cart $cart): self
    {
        $itemsData = [];
        foreach ($cart->getItems() as $item) {
            $itemsData[] = [
                'id' => $item->getId(),
                'product_id' => $item->getProductId(),
                'product_variant_id' => $item->getProductVariantId(),
                'quantity' => $item->getQuantity(),
            ];
        }

        return new self(
            id: $cart->getId(),
            userId: $cart->getUserId(),
            sessionId: $cart->getSessionId(),
            items: $itemsData
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'session_id' => $this->sessionId,
            'items' => $this->items,
        ];
    }
}
