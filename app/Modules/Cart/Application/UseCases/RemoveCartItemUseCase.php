<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;

class RemoveCartItemUseCase
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly GetCartUseCase $getCartUseCase
    ) {}

    public function execute(?int $userId, ?string $sessionId, int $productId, ?int $productVariantId): Cart
    {
        $cart = $this->getCartUseCase->execute($userId, $sessionId);
        
        $newItems = array_filter($cart->items, function ($item) use ($productId, $productVariantId) {
            return !($item->productId === $productId && $item->productVariantId === $productVariantId);
        });

        $updatedCart = new Cart(
            id: $cart->id,
            userId: $userId,
            sessionId: $sessionId,
            items: array_values($newItems)
        );

        return $this->cartRepository->save($updatedCart);
    }
}
