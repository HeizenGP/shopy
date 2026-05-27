<?php

namespace App\Modules\Inventory\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryEloquent extends Model
{
    protected $table = 'inventories';

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'stock',
        'low_stock_threshold',
    ];

    protected $casts = [
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];
}
