<?php

use App\Modules\Catalog\Infrastructure\Database\ProductModel;
use App\Modules\Cart\Infrastructure\Database\CartModel;
use App\Modules\Cart\Infrastructure\Database\CartItemModel;
use App\Modules\Orders\Infrastructure\Database\OrderModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can place an order successfully', function () {
    $product = ProductModel::create([
        'name' => 'Super Shirt',
        'slug' => 'super-shirt',
        'price' => 30.00,
        'status' => 'published',
        'stock' => 10,
    ]);

    $cart = CartModel::create(['session_id' => 'test_session']);
    CartItemModel::create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response = $this->postJson('/api/orders', [
        'session_id' => 'test_session',
        'shipping_address' => '123 Main St',
        'billing_address' => '123 Main St',
        'customer_name' => 'Jane Doe',
        'customer_email' => 'jane@example.com',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'status' => 'pending',
            'total_amount' => 30.00,
            'customer_name' => 'Jane Doe',
        ]);

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Jane Doe',
        'total_amount' => 30.00,
    ]);

    // Cart should be empty
    $this->assertDatabaseMissing('cart_items', [
        'cart_id' => $cart->id,
    ]);
});
