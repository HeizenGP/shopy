<?php

namespace App\Modules\Cart\Infrastructure\Adapters;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Entities\CartItem;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;
use App\Modules\Cart\Infrastructure\Database\Models\CartEloquent;
use App\Modules\Cart\Infrastructure\Database\Models\CartItemEloquent;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function findBySessionOrUser(?int $userId, ?string $sessionId): ?Cart
    {
        $query = CartEloquent::query()->with('items');

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return null;
        }

        $eloquent = $query->first();

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(Cart $cart): Cart
    {
        // First check if cart exists
        $eloquent = null;
        if ($cart->id > 0) {
            $eloquent = CartEloquent::find($cart->id);
        }

        if (!$eloquent) {
            $query = CartEloquent::query();
            if ($cart->userId) {
                $query->where('user_id', $cart->userId);
            } elseif ($cart->sessionId) {
                $query->where('session_id', $cart->sessionId);
            }
            $eloquent = $query->first();
        }

        if (!$eloquent) {
            $eloquent = CartEloquent::create([
                'user_id' => $cart->userId,
                'session_id' => $cart->sessionId,
            ]);
        } else {
            // Update user_id if it was guest and is now logged in
            if ($cart->userId && !$eloquent->user_id) {
                $eloquent->update(['user_id' => $cart->userId]);
            }
        }

        // Sync Items
        $itemIds = [];
        foreach ($cart->items as $item) {
            $itemEloquent = $eloquent->items()->updateOrCreate(
                [
                    'product_id' => $item->productId,
                    'product_variant_id' => $item->productVariantId,
                ],
                [
                    'quantity' => $item->quantity,
                ]
            );
            $itemIds[] = $itemEloquent->id;
        }

        // Delete items no longer in cart
        $eloquent->items()->whereNotIn('id', $itemIds)->delete();

        return $this->toDomain($eloquent->load('items'));
    }

    public function delete(int $id): bool
    {
        $eloquent = CartEloquent::find($id);
        if ($eloquent) {
            return $eloquent->delete();
        }
        return false;
    }

    private function toDomain(CartEloquent $eloquent): Cart
    {
        $items = $eloquent->items->map(function ($item) {
            return new CartItem(
                id: (int) $item->id,
                productId: (int) $item->product_id,
                productVariantId: $item->product_variant_id ? (int) $item->product_variant_id : null,
                quantity: (int) $item->quantity
            );
        })->toArray();

        return new Cart(
            id: $eloquent->id,
            userId: $eloquent->user_id,
            sessionId: $eloquent->session_id,
            items: $items
        );
    }
}
