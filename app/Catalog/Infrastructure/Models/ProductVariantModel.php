<?php

namespace App\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'regular_price',
        'sale_price',
        'weight',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
