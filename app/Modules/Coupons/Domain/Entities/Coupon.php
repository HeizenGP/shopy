<?php

namespace App\Modules\Coupons\Domain\Entities;

class Coupon
{
    public function __construct(
        public readonly int $id,
        public readonly string $code,
        public readonly string $type, // 'percentage' or 'fixed'
        public readonly float $value,
        public readonly ?float $minOrderAmount = null,
        public readonly ?string $expiresAt = null,
        public readonly ?int $usageLimit = null,
        public readonly int $timesUsed = 0,
        public readonly bool $isActive = true
    ) {}

    public function isValid(float $orderAmount): bool
    {
        if (!$this->isActive) {
            return false;
        }

        if ($this->expiresAt && strtotime($this->expiresAt) < time()) {
            return false;
        }

        if ($this->usageLimit !== null && $this->timesUsed >= $this->usageLimit) {
            return false;
        }

        if ($this->minOrderAmount !== null && $orderAmount < $this->minOrderAmount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->isValid($orderAmount)) {
            return 0.00;
        }

        if ($this->type === 'percentage') {
            return round($orderAmount * ($this->value / 100), 2);
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        }

        return 0.00;
    }
}
