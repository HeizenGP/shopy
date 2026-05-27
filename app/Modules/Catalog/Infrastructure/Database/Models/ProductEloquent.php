<?php

namespace App\Modules\Catalog\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductEloquent extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariantEloquent::class, 'product_id');
    }
}
