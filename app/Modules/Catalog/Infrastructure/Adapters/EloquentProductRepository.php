<?php

namespace App\Modules\Catalog\Infrastructure\Adapters;

use App\Modules\Catalog\Domain\Entities\Product;
use App\Modules\Catalog\Domain\Entities\Variant;
use App\Modules\Catalog\Domain\Entities\VariantOption;
use App\Modules\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\VariantOptionEloquent;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findAll(array $filters = []): array
    {
        $query = ProductEloquent::query()->with(['variants.options']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (!empty($filters['active_only'])) {
            $query->where('is_active', true);
        }

        return $query->get()->map(fn ($el) => $this->toDomain($el))->toArray();
    }

    public function findById(int $id): ?Product
    {
        $eloquent = ProductEloquent::with(['variants.options'])->find($id);
        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findBySlug(string $slug): ?Product
    {
        $eloquent = ProductEloquent::with(['variants.options'])->where('slug', $slug)->first();
        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findVariantById(int $variantId): ?Variant
    {
        $eloquent = ProductVariantEloquent::with(['options'])->find($variantId);
        return $eloquent ? $this->toVariantDomain($eloquent) : null;
    }

    public function save(Product $product): Product
    {
        $eloquent = ProductEloquent::updateOrCreate(
            ['id' => $product->id > 0 ? $product->id : null],
            [
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'category' => $product->category,
                'image' => $product->image,
                'is_active' => $product->isActive,
            ]
        );

        // Sync Variants
        if (!empty($product->variants)) {
            // Delete existing not in the list if updating
            $variantIds = array_column($product->variants, 'id');
            $eloquent->variants()->whereNotIn('id', $variantIds)->delete();

            foreach ($product->variants as $variant) {
                $variantEloquent = $eloquent->variants()->updateOrCreate(
                    ['id' => $variant->id > 0 ? $variant->id : null],
                    [
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                    ]
                );

                // Options
                $optionIds = array_column($variant->options, 'id');
                $variantEloquent->options()->whereNotIn('id', $optionIds)->delete();

                foreach ($variant->options as $opt) {
                    $variantEloquent->options()->updateOrCreate(
                        ['id' => $opt->id > 0 ? $opt->id : null],
                        [
                            'name' => $opt->name,
                            'value' => $opt->value,
                        ]
                    );
                }
            }
        }

        return $this->findById($eloquent->id);
    }

    public function delete(int $id): bool
    {
        $eloquent = ProductEloquent::find($id);
        if ($eloquent) {
            return $eloquent->delete();
        }
        return false;
    }

    private function toDomain(ProductEloquent $eloquent): Product
    {
        $variants = $eloquent->variants->map(fn ($v) => $this->toVariantDomain($v))->toArray();

        return new Product(
            id: $eloquent->id,
            name: $eloquent->name,
            slug: $eloquent->slug,
            description: $eloquent->description,
            price: (float) $eloquent->price,
            category: $eloquent->category,
            image: $eloquent->image,
            isActive: $eloquent->is_active,
            variants: $variants
        );
    }

    private function toVariantDomain(ProductVariantEloquent $eloquent): Variant
    {
        $options = $eloquent->options->map(function ($opt) {
            return new VariantOption(
                id: $opt->id,
                variantId: $opt->product_variant_id,
                name: $opt->name,
                value: $opt->value
            );
        })->toArray();

        return new Variant(
            id: $eloquent->id,
            productId: $eloquent->product_id,
            sku: $eloquent->sku,
            price: (float) $eloquent->price,
            options: $options
        );
    }
}
