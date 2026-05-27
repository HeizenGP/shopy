<?php

namespace App\Modules\Coupons\Infrastructure\Adapters;

use App\Modules\Coupons\Domain\Entities\Coupon;
use App\Modules\Coupons\Domain\Repositories\CouponRepository;
use App\Modules\Coupons\Infrastructure\Database\CouponModel;
use DateTime;

class EloquentCouponRepository implements CouponRepository
{
    public function findByCode(string $code): ?Coupon
    {
        $model = CouponModel::where('code', $code)->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function save(Coupon $coupon): Coupon
    {
        $model = CouponModel::updateOrCreate(
            ['id' => $coupon->getId()],
            [
                'code' => $coupon->getCode(),
                'type' => $coupon->getType(),
                'value' => $coupon->getValue(),
                'starts_at' => $coupon->getStartsAt(),
                'expires_at' => $coupon->getExpiresAt(),
                'usage_limit' => $coupon->getUsageLimit(),
                'used_count' => $coupon->getUsedCount(),
            ]
        );

        return $this->toDomain($model);
    }

    private function toDomain(CouponModel $model): Coupon
    {
        return new Coupon(
            id: $model->id,
            code: $model->code,
            type: $model->type,
            value: (float) $model->value,
            startsAt: $model->starts_at ? new DateTime($model->starts_at->format('Y-m-d H:i:s')) : null,
            expiresAt: $model->expires_at ? new DateTime($model->expires_at->format('Y-m-d H:i:s')) : null,
            usageLimit: $model->usage_limit,
            usedCount: $model->used_count
        );
    }
}
