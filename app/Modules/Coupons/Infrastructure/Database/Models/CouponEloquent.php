<?php

namespace App\Modules\Coupons\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;

class CouponEloquent extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'expires_at',
        'usage_limit',
        'times_used',
        'is_active',
    ];

    protected $casts = [
        'value' => 'float',
        'min_order_amount' => 'float',
        'times_used' => 'integer',
        'usage_limit' => 'integer',
        'is_active' => 'boolean',
    ];
}
