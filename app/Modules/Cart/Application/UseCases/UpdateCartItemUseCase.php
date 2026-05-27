<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Entities\CartItem;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;
use Exception;

class UpdateCartItemUseCase
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly GetCartUseCase $getCartUseCase
    ) {}

    public function execute(?int $userId, ?string $sessionId, int $productId, ?int $productVariantId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            return $this->appRemove($userId, $sessionId, $productId, $productVariantId);
        }

        // Verify stock
        $inventory = \App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent::where('product_id', $productId)
            ->where('product_variant_id', $productVariantId)
            ->first();
        
        $stockAvailable = $inventory ? $inventory->stock : 0;
        if ($stockAvailable < $quantity) {
            throw new Exception("Stock insuficiente. Solamente hay {$stockAvailable} unidades disponibles.");
        }

        $cart = $this->getCartUseCase->execute($userId, $sessionId);

        $newItems = [];
        foreach ($cart->items as $item) {
            if ($item->productId === $productId && $item->productVariantId === $productVariantId) {
                $newItems[] = new CartItem(
                    id: $item->id,
                    productId: $item->productId,
                    productVariantId: $item->productVariantId,
                    quantity: $quantity
                );
            } else {
                $newItems[] = $item;
            }
        }

        $updatedCart = new Cart(
            id: $cart->id,
            userId: $userId,
            sessionId: $sessionId,
            items: $newItems
        );

        return $this->cartRepository->save($updatedCart);
    }

    private function appRemove(?int $userId, ?string $sessionId, int $productId, ?int $productVariantId): Cart
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
