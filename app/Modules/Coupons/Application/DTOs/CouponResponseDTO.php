<?php

namespace App\Modules\Coupons\Application\DTOs;

use App\Modules\Coupons\Domain\Entities\Coupon;

class CouponResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $code,
        public readonly string $type,
        public readonly float $value,
        public readonly bool $isValid
    ) {}

    public static function fromEntity(Coupon $coupon): self
    {
        return new self(
            id: $coupon->getId(),
            code: $coupon->getCode(),
            type: $coupon->getType(),
            value: $coupon->getValue(),
            isValid: $coupon->isValid()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'is_valid' => $this->isValid,
        ];
    }
}
