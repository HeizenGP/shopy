<?php

use App\Models\User;
use App\Modules\Catalog\Infrastructure\Database\ProductModel;
use App\Modules\Catalog\Infrastructure\Database\ProductVariantModel;
use App\Modules\Cart\Infrastructure\Database\CartModel;
use App\Modules\Cart\Infrastructure\Database\CartItemModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user or guest can fetch their cart', function () {
    $response = $this->getJson('/api/cart?session_id=test_session');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'user_id',
            'session_id',
            'items',
        ]);
});

test('user can add item to cart', function () {
    $product = ProductModel::create([
        'name' => 'Widget',
        'slug' => 'widget',
        'price' => 10.00,
        'status' => 'published',
        'stock' => 100,
    ]);

    $response = $this->postJson('/api/cart/add', [
        'session_id' => 'test_session',
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
});
