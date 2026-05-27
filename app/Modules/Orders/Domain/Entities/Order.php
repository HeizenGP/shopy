<?php

namespace App\Modules\Orders\Domain\Entities;

use InvalidArgumentException;

class Order
{
    /**
     * @param OrderItem[] $items
     */
    public function __construct(
        private ?int $id,
        private ?int $userId,
        private string $status, // pending, paid, processing, shipped, delivered, cancelled
        private float $totalAmount,
        private float $discountAmount,
        private ?string $couponCode,
        private string $shippingAddress,
        private string $billingAddress,
        private string $customerName,
        private string $customerEmail,
        private array $items = []
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function updateStatus(string $newStatus): void
    {
        $allowed = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $allowed)) {
            throw new InvalidArgumentException("Estado de pedido no válido: {$newStatus}");
        }
        $this->status = $newStatus;
    }
}
