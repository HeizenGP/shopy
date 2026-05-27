<?php

namespace App\Modules\Orders\Application\DTOs;

use App\Modules\Orders\Domain\Entities\Order;

class OrderResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId,
        public readonly string $status,
        public readonly float $totalAmount,
        public readonly float $discountAmount,
        public readonly ?string $couponCode,
        public readonly string $shippingAddress,
        public readonly string $billingAddress,
        public readonly string $customerName,
        public readonly string $customerEmail,
        public readonly array $items
    ) {}

    public static function fromEntity(Order $order): self
    {
        $itemsData = [];
        foreach ($order->getItems() as $item) {
            $itemsData[] = [
                'id' => $item->getId(),
                'product_id' => $item->getProductId(),
                'product_variant_id' => $item->getProductVariantId(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'subtotal' => $item->getSubtotal(),
            ];
        }

        return new self(
            id: $order->getId(),
            userId: $order->getUserId(),
            status: $order->getStatus(),
            totalAmount: $order->getTotalAmount(),
            discountAmount: $order->getDiscountAmount(),
            couponCode: $order->getCouponCode(),
            shippingAddress: $order->getShippingAddress(),
            billingAddress: $order->getBillingAddress(),
            customerName: $order->getCustomerName(),
            customerEmail: $order->getCustomerEmail(),
            items: $itemsData
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'status' => $this->status,
            'total_amount' => $this->totalAmount,
            'discount_amount' => $this->discountAmount,
            'coupon_code' => $this->couponCode,
            'shipping_address' => $this->shippingAddress,
            'billing_address' => $this->billingAddress,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'items' => $this->items,
        ];
    }
}
