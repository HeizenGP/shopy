<?php

namespace App\Catalog\Infrastructure\Repositories;

use App\Catalog\Application\DTOs\CreateProductData;
use App\Catalog\Application\DTOs\UpdateProductData;
use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\ProductModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginateForAdmin(int $perPage = 12, array $filters = []): LengthAwarePaginator
    {
        return ProductModel::query()
            ->with(['brand', 'mainCategory', 'images'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['brand_id'] ?? null, fn ($query, int $brandId) => $query->where('brand_id', $brandId))
            ->when($filters['category_id'] ?? null, function ($query, int $categoryId): void {
                $query->where(function ($query) use ($categoryId): void {
                    $query->where('main_category_id', $categoryId)
                        ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->where('categories.id', $categoryId));
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function published(int $perPage = 12): LengthAwarePaginator
    {
        return ProductModel::query()
            ->published()
            ->with(['brand', 'mainCategory', 'images'])
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function featured(int $limit = 4): Collection
    {
        return ProductModel::query()
            ->published()
            ->where('is_featured', true)
            ->with(['brand', 'mainCategory', 'images'])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function find(int $id): ?ProductModel
    {
        return ProductModel::query()
            ->with(['brand', 'mainCategory', 'categories', 'variants', 'images'])
            ->find($id);
    }

    public function findPublishedBySlug(string $slug): ?ProductModel
    {
        return ProductModel::query()
            ->published()
            ->with(['brand', 'mainCategory', 'categories', 'variants', 'images'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(CreateProductData $data): ProductModel
    {
        return DB::transaction(function () use ($data): ProductModel {
            $product = ProductModel::query()->create($this->payload($data));
            $this->syncAssociations($product, $data->categoryIds, $data->variants);

            return $product->fresh(['brand', 'mainCategory', 'categories', 'variants']);
        });
    }

    public function update(int $id, UpdateProductData $data): ProductModel
    {
        return DB::transaction(function () use ($id, $data): ProductModel {
            $product = ProductModel::query()->findOrFail($id);
            $product->update($this->payload($data));
            $this->syncAssociations($product, $data->categoryIds, $data->variants);

            return $product->fresh(['brand', 'mainCategory', 'categories', 'variants']);
        });
    }

    public function delete(int $id): void
    {
        ProductModel::query()->findOrFail($id)->delete();
    }

    private function payload(CreateProductData|UpdateProductData $data): array
    {
        return [
            'name' => $data->name,
            'slug' => $data->slug,
            'sku' => $data->sku,
            'regular_price' => $data->regularPrice,
            'sale_price' => $data->salePrice,
            'status' => $data->status,
            'brand_id' => $data->brandId,
            'main_category_id' => $data->mainCategoryId,
            'short_description' => $data->shortDescription,
            'description' => $data->description,
            'is_featured' => $data->isFeatured,
            'has_variants' => $data->hasVariants,
            'published_at' => $data->status === ProductStatus::Published ? now() : null,
        ];
    }

    private function syncAssociations(ProductModel $product, array $categoryIds, array $variants): void
    {
        $product->categories()->sync($categoryIds);
        $product->variants()->delete();

        foreach ($variants as $variant) {
            if (($variant['name'] ?? '') === '' || ($variant['sku'] ?? '') === '') {
                continue;
            }

            $product->variants()->create([
                'name' => $variant['name'],
                'sku' => $variant['sku'],
                'regular_price' => $variant['regular_price'] ?? null,
                'sale_price' => $variant['sale_price'] ?? null,
                'is_active' => (bool) ($variant['is_active'] ?? true),
                'is_default' => (bool) ($variant['is_default'] ?? false),
            ]);
        }
    }
}
