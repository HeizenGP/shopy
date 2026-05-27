<?php

namespace App\Modules\Orders\Infrastructure\Adapters;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Entities\OrderItem;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Orders\Infrastructure\Database\Models\OrderEloquent;
use App\Modules\Orders\Infrastructure\Database\Models\OrderItemEloquent;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        $eloquent = OrderEloquent::with('items')->find($id);
        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findByUserId(int $userId): array
    {
        return OrderEloquent::with('items')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($el) => $this->toDomain($el))
            ->toArray();
    }

    public function findAll(): array
    {
        return OrderEloquent::with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($el) => $this->toDomain($el))
            ->toArray();
    }

    public function save(Order $order): Order
    {
        $eloquent = OrderEloquent::create([
            'user_id' => $order->userId,
            'status' => $order->status->value,
            'subtotal' => $order->subtotal,
            'discount_amount' => $order->discountAmount,
            'total' => $order->total,
            'coupon_id' => $order->couponId,
            'billing_name' => $order->billingName,
            'billing_email' => $order->billingEmail,
            'billing_address' => $order->billingAddress,
        ]);

        foreach ($order->items as $item) {
            $eloquent->items()->create([
                'product_id' => $item->productId,
                'product_variant_id' => $item->productVariantId,
                'quantity' => $item->quantity,
                'unit_price' => $item->unitPrice,
                'total' => $item->total,
            ]);
        }

        return $this->toDomain($eloquent->load('items'));
    }

    public function updateStatus(int $orderId, OrderStatus $status): void
    {
        OrderEloquent::where('id', $orderId)->update(['status' => $status->value]);
    }

    private function toDomain(OrderEloquent $eloquent): Order
    {
        $items = $eloquent->items->map(function ($item) {
            return new OrderItem(
                id: $item->id,
                productId: $item->product_id,
                productVariantId: $item->product_variant_id,
                quantity: $item->quantity,
                unitPrice: (float) $item->unit_price,
                total: (float) $item->total
            );
        })->toArray();

        return new Order(
            id: $eloquent->id,
            userId: $eloquent->user_id,
            status: OrderStatus::from($eloquent->status),
            subtotal: (float) $eloquent->subtotal,
            discountAmount: (float) $eloquent->discount_amount,
            total: (float) $eloquent->total,
            couponId: $eloquent->coupon_id,
            billingName: $eloquent->billing_name,
            billingEmail: $eloquent->billing_email,
            billingAddress: $eloquent->billing_address,
            items: $items,
            createdAt: $eloquent->created_at?->toIso8601String()
        );
    }
}
