<?php

use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
use App\Catalog\Infrastructure\Models\ProductVariantModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the public catalog routes', function (): void {
    $category = CategoryModel::query()->create([
        'name' => 'Electronica',
        'slug' => 'electronica',
        'is_active' => true,
    ]);

    $brand = BrandModel::query()->create([
        'name' => 'Nova',
        'slug' => 'nova',
        'is_active' => true,
    ]);

    $product = ProductModel::query()->create([
        'brand_id' => $brand->id,
        'main_category_id' => $category->id,
        'name' => 'Audifonos Nova Air',
        'slug' => 'audifonos-nova-air',
        'sku' => 'NOVA-AIR-001',
        'regular_price' => 189.90,
        'sale_price' => 149.90,
        'status' => ProductStatus::Published,
        'is_featured' => true,
        'published_at' => now(),
    ]);

    $product->categories()->attach($category);

    $this->get('/')->assertOk()->assertSee('Audifonos Nova Air');
    $this->get('/products')->assertOk()->assertSee('Audifonos Nova Air');
    $this->get('/products/audifonos-nova-air')->assertOk()->assertSee('NOVA-AIR-001');
    $this->get('/categories/electronica')->assertOk()->assertSee('Audifonos Nova Air');
});

it('creates a product from the admin catalog', function (): void {
    $category = CategoryModel::query()->create([
        'name' => 'Hogar',
        'slug' => 'hogar',
        'is_active' => true,
    ]);

    $this->post('/admin/catalog/products', [
        'name' => 'Lampara Desk',
        'slug' => 'lampara-desk',
        'sku' => 'LAMP-001',
        'regular_price' => 79.9,
        'status' => ProductStatus::Draft->value,
        'main_category_id' => $category->id,
        'category_ids' => [$category->id],
    ])->assertRedirect();

    $this->assertDatabaseHas('products', [
        'slug' => 'lampara-desk',
        'sku' => 'LAMP-001',
    ]);
});

it('updates a product while keeping its existing variant sku', function (): void {
    $product = ProductModel::query()->create([
        'name' => 'Polo Basic',
        'slug' => 'polo-basic',
        'sku' => 'POLO-001',
        'regular_price' => 49.9,
        'status' => ProductStatus::Draft,
    ]);

    ProductVariantModel::query()->create([
        'product_id' => $product->id,
        'name' => 'Talla M',
        'sku' => 'SKU-M-BLU',
        'regular_price' => 49.9,
    ]);

    $this->put("/admin/catalog/products/{$product->id}", [
        'name' => 'Polo Basic Actualizado',
        'slug' => 'polo-basic',
        'sku' => 'POLO-001',
        'regular_price' => 49.9,
        'status' => ProductStatus::Draft->value,
        'has_variants' => '1',
        'variants' => [
            [
                'name' => 'Talla M',
                'sku' => 'SKU-M-BLU',
                'regular_price' => 49.9,
            ],
        ],
    ])->assertRedirect();

    $this->assertDatabaseHas('product_variants', [
        'product_id' => $product->id,
        'sku' => 'SKU-M-BLU',
        'deleted_at' => null,
    ]);
});

it('allows only three category levels', function (): void {
    $parent = CategoryModel::query()->create([
        'name' => 'Ropa',
        'slug' => 'ropa',
        'is_active' => true,
    ]);

    $subcategory = CategoryModel::query()->create([
        'parent_id' => $parent->id,
        'name' => 'Hombre',
        'slug' => 'hombre',
        'is_active' => true,
    ]);

    $subsubcategory = CategoryModel::query()->create([
        'parent_id' => $subcategory->id,
        'name' => 'Polos',
        'slug' => 'polos',
        'is_active' => true,
    ]);

    $this->post('/admin/catalog/categories', [
        'parent_id' => $subcategory->id,
        'name' => 'Camisas',
        'slug' => 'camisas',
        'is_active' => '1',
    ])->assertRedirect('/admin/catalog/categories');

    $this->post('/admin/catalog/categories', [
        'parent_id' => $subsubcategory->id,
        'name' => 'Manga corta',
        'slug' => 'manga-corta',
        'is_active' => '1',
    ])->assertSessionHasErrors('parent_id');

    $this->assertDatabaseMissing('categories', [
        'slug' => 'manga-corta',
    ]);
});
