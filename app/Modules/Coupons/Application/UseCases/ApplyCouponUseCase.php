<?php

namespace App\Modules\Coupons\Application\UseCases;

use App\Modules\Coupons\Application\DTOs\CouponResponseDTO;
use App\Modules\Coupons\Domain\Repositories\CouponRepository;
use DomainException;

class ApplyCouponUseCase
{
    public function __construct(private CouponRepository $repository) {}

    public function execute(string $code, float $totalAmount): array
    {
        $coupon = $this->repository->findByCode($code);

        if (!$coupon) {
            throw new DomainException("El cupón no existe.");
        }

        if (!$coupon->isValid()) {
            throw new DomainException("El cupón no es válido o ya caducó.");
        }

        $discount = $coupon->calculateDiscount($totalAmount);

        return [
            'coupon' => CouponResponseDTO::fromEntity($coupon)->toArray(),
            'discount' => $discount,
            'new_total' => max(0.00, $totalAmount - $discount),
        ];
    }
}
