<?php

namespace App\Modules\Orders\Domain\Entities;

use App\Modules\Orders\Domain\Enums\OrderStatus;

class Order
{
    /**
     * @param OrderItem[] $items
     */
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId,
        public readonly OrderStatus $status,
        public readonly float $subtotal,
        public readonly float $discountAmount,
        public readonly float $total,
        public readonly ?int $couponId,
        public readonly string $billingName,
        public readonly string $billingEmail,
        public readonly string $billingAddress,
        public readonly array $items = [],
        public readonly ?string $createdAt = null
    ) {}
}
