<?php

namespace App\Modules\Coupons\Domain\Repositories;

use App\Modules\Coupons\Domain\Entities\Coupon;

interface CouponRepository
{
    public function findByCode(string $code): ?Coupon;
    public function save(Coupon $coupon): Coupon;
}
