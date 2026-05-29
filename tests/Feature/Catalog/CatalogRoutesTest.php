<?php

use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
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
