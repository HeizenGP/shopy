<?php

namespace App\Modules\Orders\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemEloquent extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'float',
        'total' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderEloquent::class, 'order_id');
    }
}
