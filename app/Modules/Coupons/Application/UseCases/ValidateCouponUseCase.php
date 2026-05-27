<?php

namespace App\Modules\Coupons\Application\UseCases;

use App\Modules\Coupons\Domain\Entities\Coupon;
use App\Modules\Coupons\Domain\Repositories\CouponRepositoryInterface;
use Exception;

class ValidateCouponUseCase
{
    public function __construct(
        private readonly CouponRepositoryInterface $couponRepository
    ) {}

    public function execute(string $code, float $orderAmount): array
    {
        $coupon = $this->couponRepository->findByCode(strtoupper(trim($code)));

        if (!$coupon) {
            throw new Exception("El cupón introducido no es válido.");
        }

        if (!$coupon->isValid($orderAmount)) {
            if (!$coupon->isActive) {
                throw new Exception("Este cupón está inactivo.");
            }
            if ($coupon->expiresAt && strtotime($coupon->expiresAt) < time()) {
                throw new Exception("Este cupón ha caducado.");
            }
            if ($coupon->usageLimit !== null && $coupon->timesUsed >= $coupon->usageLimit) {
                throw new Exception("Este cupón ha alcanzado su límite de usos.");
            }
            if ($coupon->minOrderAmount !== null && $orderAmount < $coupon->minOrderAmount) {
                throw new Exception("El pedido no cumple con el monto mínimo de $" . number_format($coupon->minOrderAmount, 2) . " requerido para este cupón.");
            }
            throw new Exception("El cupón no es válido para este pedido.");
        }

        $discount = $coupon->calculateDiscount($orderAmount);

        return [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount,
        ];
    }
}
