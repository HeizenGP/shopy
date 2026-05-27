<?php

namespace App\Modules\Inventory\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;

class StockAlertEloquent extends Model
{
    protected $table = 'stock_alerts';

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'message',
        'is_resolved',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];
}
