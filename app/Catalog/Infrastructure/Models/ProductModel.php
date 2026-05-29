<?php

namespace App\Catalog\Infrastructure\Models;

use App\Catalog\Domain\ValueObjects\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'main_category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'status',
        'is_featured',
        'has_variants',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'status' => ProductStatus::class,
            'is_featured' => 'boolean',
            'has_variants' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Published->value);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class, 'brand_id');
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'main_category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CategoryModel::class, 'product_category', 'product_id', 'category_id')->withTimestamps();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariantModel::class, 'product_id')->orderByDesc('is_default')->orderBy('name');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImageModel::class, 'product_id')->orderByDesc('is_main')->orderBy('sort_order');
    }

    public function formattedPrice(): string
    {
        $price = $this->sale_price ?: $this->regular_price;

        return 'S/ '.number_format((float) $price, 2);
    }
}
