<?php

namespace App\Modules\Cart\Infrastructure\Adapters;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Entities\CartItem;
use App\Modules\Cart\Domain\Repositories\CartRepository;
use App\Modules\Cart\Infrastructure\Database\CartItemModel;
use App\Modules\Cart\Infrastructure\Database\CartModel;

class EloquentCartRepository implements CartRepository
{
    public function findByUserId(int $userId): ?Cart
    {
        $model = CartModel::with('items')->where('user_id', $userId)->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findBySessionId(string $sessionId): ?Cart
    {
        $model = CartModel::with('items')->where('session_id', $sessionId)->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function save(Cart $cart): Cart
    {
        $model = CartModel::updateOrCreate(
            ['id' => $cart->getId()],
            [
                'user_id' => $cart->getUserId(),
                'session_id' => $cart->getSessionId(),
            ]
        );

        // Simple sync items
        $existingItemIds = [];
        foreach ($cart->getItems() as $item) {
            $itemModel = CartItemModel::updateOrCreate(
                [
                    'cart_id' => $model->id,
                    'product_id' => $item->getProductId(),
                    'product_variant_id' => $item->getProductVariantId(),
                ],
                [
                    'quantity' => $item->getQuantity(),
                ]
            );
            $existingItemIds[] = $itemModel->id;
        }

        // Delete items no longer in cart
        CartItemModel::where('cart_id', $model->id)
            ->whereNotIn('id', $existingItemIds)
            ->delete();

        return $this->toDomain($model->load('items'));
    }

    public function createForUser(int $userId): Cart
    {
        $model = CartModel::create(['user_id' => $userId]);
        return $this->toDomain($model);
    }

    public function createForSession(string $sessionId): Cart
    {
        $model = CartModel::create(['session_id' => $sessionId]);
        return $this->toDomain($model);
    }

    private function toDomain(CartModel $model): Cart
    {
        $items = [];
        foreach ($model->items as $itemModel) {
            $items[] = new CartItem(
                id: $itemModel->id,
                cartId: $itemModel->cart_id,
                productId: $itemModel->product_id,
                productVariantId: $itemModel->product_variant_id,
                quantity: $itemModel->quantity
            );
        }

        return new Cart(
            id: $model->id,
            userId: $model->user_id,
            sessionId: $model->session_id,
            items: $items
        );
    }
}
