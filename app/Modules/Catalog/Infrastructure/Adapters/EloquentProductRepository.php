<?php

namespace App\Modules\Catalog\Infrastructure\Adapters;

use App\Modules\Catalog\Domain\Entities\Product;
use App\Modules\Catalog\Domain\Entities\ProductVariant;
use App\Modules\Catalog\Domain\Repositories\ProductRepository;
use App\Modules\Catalog\Domain\ValueObjects\Price;
use App\Modules\Catalog\Infrastructure\Database\ProductModel;
use App\Modules\Catalog\Infrastructure\Database\ProductVariantModel;

class EloquentProductRepository implements ProductRepository
{
    public function findById(int $id): ?Product
    {
        $model = ProductModel::with('variants')->find($id);

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findBySlug(string $slug): ?Product
    {
        $model = ProductModel::with('variants')->where('slug', $slug)->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function save(Product $product): Product
    {
        $model = ProductModel::updateOrCreate(
            ['id' => $product->getId()],
            [
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()->getAmount(),
                'status' => $product->getStatus(),
                'stock' => $product->getStock(),
            ]
        );

        // Sync variants
        foreach ($product->getVariants() as $variant) {
            ProductVariantModel::updateOrCreate(
                ['id' => $variant->getId()],
                [
                    'product_id' => $model->id,
                    'name' => $variant->getName(),
                    'sku' => $variant->getSku(),
                    'price' => $variant->getPrice() ? $variant->getPrice()->getAmount() : null,
                    'stock' => $variant->getStock(),
                ]
            );
        }

        return $this->toDomain($model->load('variants'));
    }

    public function findAllPublished(): array
    {
        $models = ProductModel::with('variants')
            ->where('status', 'published')
            ->get();

        return $models->map(fn ($model) => $this->toDomain($model))->toArray();
    }

    private function toDomain(ProductModel $model): Product
    {
        $variants = [];
        foreach ($model->variants as $variantModel) {
            $variants[] = new ProductVariant(
                id: $variantModel->id,
                productId: $variantModel->product_id,
                name: $variantModel->name,
                sku: $variantModel->sku,
                price: $variantModel->price !== null ? new Price($variantModel->price) : null,
                stock: $variantModel->stock
            );
        }

        return new Product(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            description: $model->description,
            price: new Price($model->price),
            status: $model->status,
            stock: $model->stock,
            variants: $variants
        );
    }
}
