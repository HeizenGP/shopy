<?php

namespace App\Modules\Orders\Infrastructure\Adapters;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Entities\OrderItem;
use App\Modules\Orders\Domain\Repositories\OrderRepository;
use App\Modules\Orders\Infrastructure\Database\OrderItemModel;
use App\Modules\Orders\Infrastructure\Database\OrderModel;

class EloquentOrderRepository implements OrderRepository
{
    public function findById(int $id): ?Order
    {
        $model = OrderModel::with('items')->find($id);

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function save(Order $order): Order
    {
        $model = OrderModel::updateOrCreate(
            ['id' => $order->getId()],
            [
                'user_id' => $order->getUserId(),
                'status' => $order->getStatus(),
                'total_amount' => $order->getTotalAmount(),
                'discount_amount' => $order->getDiscountAmount(),
                'coupon_code' => $order->getCouponCode(),
                'shipping_address' => $order->getShippingAddress(),
                'billing_address' => $order->getBillingAddress(),
                'customer_name' => $order->getCustomerName(),
                'customer_email' => $order->getCustomerEmail(),
            ]
        );

        foreach ($order->getItems() as $item) {
            OrderItemModel::updateOrCreate(
                [
                    'order_id' => $model->id,
                    'product_id' => $item->getProductId(),
                    'product_variant_id' => $item->getProductVariantId(),
                ],
                [
                    'name' => $item->getName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                ]
            );
        }

        return $this->toDomain($model->load('items'));
    }

    public function findByUserId(int $userId): array
    {
        $models = OrderModel::with('items')->where('user_id', $userId)->get();

        return $models->map(fn ($model) => $this->toDomain($model))->toArray();
    }

    private function toDomain(OrderModel $model): Order
    {
        $items = [];
        foreach ($model->items as $itemModel) {
            $items[] = new OrderItem(
                id: $itemModel->id,
                orderId: $itemModel->order_id,
                productId: $itemModel->product_id,
                productVariantId: $itemModel->product_variant_id,
                name: $itemModel->name,
                quantity: $itemModel->quantity,
                price: (float) $itemModel->price
            );
        }

        return new Order(
            id: $model->id,
            userId: $model->user_id,
            status: $model->status,
            totalAmount: (float) $model->total_amount,
            discountAmount: (float) $model->discount_amount,
            couponCode: $model->coupon_code,
            shippingAddress: $model->shipping_address,
            billingAddress: $model->billing_address,
            customerName: $model->customer_name,
            customerEmail: $model->customer_email,
            items: $items
        );
    }
}
