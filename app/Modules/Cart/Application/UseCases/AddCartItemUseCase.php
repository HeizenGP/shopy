<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Entities\CartItem;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;
use Exception;

class AddCartItemUseCase
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly GetCartUseCase $getCartUseCase
    ) {}

    public function execute(?int $userId, ?string $sessionId, int $productId, ?int $productVariantId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            throw new Exception("La cantidad debe ser mayor que cero.");
        }

        // Verify that the product and variant exist in the Catalog
        $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($productId);
        if (!$product) {
            throw new Exception("El producto no existe.");
        }

        if ($productVariantId) {
            $variant = $product->variants()->find($productVariantId);
            if (!$variant) {
                throw new Exception("La variante de producto seleccionada no existe.");
            }
        }

        // Verify stock
        $inventory = \App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent::where('product_id', $productId)
            ->where('product_variant_id', $productVariantId)
            ->first();
        
        $stockAvailable = $inventory ? $inventory->stock : 0;
        
        $cart = $this->getCartUseCase->execute($userId, $sessionId);

        // Find if item already exists in cart to get existing quantity
        $existingQty = 0;
        foreach ($cart->items as $item) {
            if ($item->productId === $productId && $item->productVariantId === $productVariantId) {
                $existingQty = $item->quantity;
                break;
            }
        }

        $totalRequested = $existingQty + $quantity;
        if ($stockAvailable < $totalRequested) {
            throw new Exception("Stock insuficiente. Solamente hay {$stockAvailable} unidades disponibles.");
        }

        // Build new items list
        $newItems = [];
        $found = false;
        foreach ($cart->items as $item) {
            if ($item->productId === $productId && $item->productVariantId === $productVariantId) {
                $newItems[] = new CartItem(
                    id: $item->id,
                    productId: $item->productId,
                    productVariantId: $item->productVariantId,
                    quantity: $totalRequested
                );
                $found = true;
            } else {
                $newItems[] = $item;
            }
        }

        if (!$found) {
            $newItems[] = new CartItem(
                id: 0,
                productId: $productId,
                productVariantId: $productVariantId,
                quantity: $quantity
            );
        }

        $updatedCart = new Cart(
            id: $cart->id,
            userId: $userId,
            sessionId: $sessionId,
            items: $newItems
        );

        return $this->cartRepository->save($updatedCart);
    }
}
