<?php

namespace App\Modules\Catalog\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantEloquent extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductEloquent::class, 'product_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(VariantOptionEloquent::class, 'product_variant_id');
    }
}
