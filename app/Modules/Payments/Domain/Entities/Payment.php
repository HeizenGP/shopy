<?php

namespace App\Modules\Payments\Domain\Entities;

class Payment
{
    public function __construct(
        private ?int $id,
        private int $orderId,
        private string $gateway,
        private ?string $transactionId,
        private float $amount,
        private string $status // pending, completed, failed, refunded
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
