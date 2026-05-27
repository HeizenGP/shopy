<?php

namespace App\Modules\Coupons\Domain\Repositories;

use App\Modules\Coupons\Domain\Entities\Coupon;

interface CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon;
    public function findById(int $id): ?Coupon;
    public function save(Coupon $coupon): Coupon;
    public function incrementTimesUsed(int $couponId): void;
}
