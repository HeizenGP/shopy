<?php

namespace App\Modules\Catalog\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantOptionEloquent extends Model
{
    protected $table = 'product_variant_options';

    protected $fillable = [
        'product_variant_id',
        'name',
        'value',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariantEloquent::class, 'product_variant_id');
    }
}
