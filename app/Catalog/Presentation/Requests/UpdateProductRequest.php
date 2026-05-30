<?php

namespace App\Catalog\Presentation\Requests;

use App\Catalog\Application\DTOs\UpdateProductData;
use App\Catalog\Domain\ValueObjects\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('products', 'slug')->ignore($productId)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:regular_price'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'main_category_id' => ['nullable', 'exists:categories,id'],
            'category_ids' => ['array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'has_variants' => ['nullable', 'boolean'],
            'variants' => ['array'],
            'variants.*.name' => ['nullable', 'string', 'max:180'],
            'variants.*.sku' => ['nullable', 'string', 'max:120', 'distinct'],
            'variants.*.regular_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function toData(): UpdateProductData
    {
        $validated = $this->validated();

        return new UpdateProductData(
            name: $validated['name'],
            slug: $validated['slug'] ?: Str::slug($validated['name']),
            sku: $validated['sku'] ?? null,
            regularPrice: (float) $validated['regular_price'],
            salePrice: isset($validated['sale_price']) ? (float) $validated['sale_price'] : null,
            status: ProductStatus::from($validated['status']),
            brandId: $validated['brand_id'] ?? null,
            mainCategoryId: $validated['main_category_id'] ?? null,
            shortDescription: $validated['short_description'] ?? null,
            description: $validated['description'] ?? null,
            isFeatured: $this->boolean('is_featured'),
            hasVariants: $this->boolean('has_variants'),
            categoryIds: $validated['category_ids'] ?? [],
            variants: $validated['variants'] ?? [],
        );
    }
}
