<?php

namespace App\Modules\Coupons\Infrastructure\Adapters;

use App\Modules\Coupons\Domain\Entities\Coupon;
use App\Modules\Coupons\Domain\Repositories\CouponRepositoryInterface;
use App\Modules\Coupons\Infrastructure\Database\Models\CouponEloquent;

class EloquentCouponRepository implements CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon
    {
        $eloquent = CouponEloquent::where('code', $code)->first();
        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findById(int $id): ?Coupon
    {
        $eloquent = CouponEloquent::find($id);
        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(Coupon $coupon): Coupon
    {
        $eloquent = CouponEloquent::updateOrCreate(
            ['id' => $coupon->id > 0 ? $coupon->id : null],
            [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'min_order_amount' => $coupon->minOrderAmount,
                'expires_at' => $coupon->expiresAt,
                'usage_limit' => $coupon->usageLimit,
                'times_used' => $coupon->timesUsed,
                'is_active' => $coupon->isActive,
            ]
        );

        return $this->toDomain($eloquent);
    }

    public function incrementTimesUsed(int $couponId): void
    {
        CouponEloquent::where('id', $couponId)->increment('times_used');
    }

    private function toDomain(CouponEloquent $eloquent): Coupon
    {
        return new Coupon(
            id: $eloquent->id,
            code: $eloquent->code,
            type: $eloquent->type,
            value: (float) $eloquent->value,
            minOrderAmount: $eloquent->min_order_amount ? (float) $eloquent->min_order_amount : null,
            expiresAt: $eloquent->expires_at ? $eloquent->expires_at : null,
            usageLimit: $eloquent->usage_limit,
            timesUsed: $eloquent->times_used,
            isActive: $eloquent->is_active
        );
    }
}
