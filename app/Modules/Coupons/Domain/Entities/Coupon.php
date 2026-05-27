<?php

namespace App\Modules\Coupons\Domain\Entities;

use DateTime;
use DomainException;

class Coupon
{
    public function __construct(
        private ?int $id,
        private string $code,
        private string $type, // fixed, percentage
        private float $value,
        private ?DateTime $startsAt,
        private ?DateTime $expiresAt,
        private ?int $usageLimit,
        private int $usedCount
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getStartsAt(): ?DateTime
    {
        return $this->startsAt;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function getUsedCount(): int
    {
        return $this->usedCount;
    }

    public function isValid(): bool
    {
        $now = new DateTime();

        if ($this->startsAt && $this->startsAt > $now) {
            return false;
        }

        if ($this->expiresAt && $this->expiresAt < $now) {
            return false;
        }

        if ($this->usageLimit !== null && $this->usedCount >= $this->usageLimit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $totalAmount): float
    {
        if (!$this->isValid()) {
            throw new DomainException("El cupón no es válido o ha expirado.");
        }

        if ($this->type === 'percentage') {
            return round($totalAmount * ($this->value / 100), 2);
        }

        // fixed
        return min($this->value, $totalAmount);
    }
}
