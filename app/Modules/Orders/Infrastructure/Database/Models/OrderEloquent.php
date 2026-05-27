<?php

namespace App\Modules\Orders\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderEloquent extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'discount_amount',
        'total',
        'coupon_id',
        'billing_name',
        'billing_email',
        'billing_address',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'total' => 'float',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemEloquent::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Auth\Infrastructure\Database\Models\UserEloquent::class, 'user_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Coupons\Infrastructure\Database\Models\CouponEloquent::class, 'coupon_id');
    }
}
