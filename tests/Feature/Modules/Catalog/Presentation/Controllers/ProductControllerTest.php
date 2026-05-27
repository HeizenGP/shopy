<?php

use App\Modules\Catalog\Infrastructure\Database\ProductModel;
use App\Modules\Catalog\Infrastructure\Database\ProductVariantModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can list all published products', function () {
    $product1 = ProductModel::create([
        'name' => 'Shirt',
        'slug' => 'shirt',
        'price' => 20.00,
        'status' => 'published',
        'stock' => 10,
    ]);

    ProductVariantModel::create([
        'product_id' => $product1->id,
        'name' => 'M',
        'sku' => 'SHIRT-M',
        'price' => 22.00,
        'stock' => 5,
    ]);

    $product2 = ProductModel::create([
        'name' => 'Pants',
        'slug' => 'pants',
        'price' => 40.00,
        'status' => 'draft',
        'stock' => 5,
    ]);

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'Shirt'])
        ->assertJsonMissing(['name' => 'Pants']);
});

test('guest can get a published product by slug', function () {
    $product = ProductModel::create([
        'name' => 'Shirt',
        'slug' => 'shirt',
        'price' => 20.00,
        'status' => 'published',
        'stock' => 10,
    ]);

    $response = $this->getJson('/api/products/shirt');

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Shirt', 'slug' => 'shirt']);
});

test('getting a non-existent or unpublished product returns 404', function () {
    $product = ProductModel::create([
        'name' => 'Pants',
        'slug' => 'pants',
        'price' => 40.00,
        'status' => 'draft',
        'stock' => 5,
    ]);

    $response = $this->getJson('/api/products/pants');
    $response->assertStatus(404);

    $response2 = $this->getJson('/api/products/nonexistent');
    $response2->assertStatus(404);
});
