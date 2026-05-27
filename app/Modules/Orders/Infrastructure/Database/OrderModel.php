<?php

namespace App\Modules\Orders\Infrastructure\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'discount_amount',
        'coupon_code',
        'shipping_address',
        'billing_address',
        'customer_name',
        'customer_email',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
