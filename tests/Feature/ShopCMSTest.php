<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\StockAlertEloquent;
use App\Modules\Coupons\Infrastructure\Database\Models\CouponEloquent;
use App\Modules\Orders\Infrastructure\Database\Models\OrderEloquent;
use App\Modules\Reviews\Infrastructure\Database\Models\ReviewEloquent;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed basic items for tests
    $this->product = ProductEloquent::create([
        'name' => 'Test Product',
        'slug' => 'test-product',
        'description' => 'A wonderful test product description.',
        'price' => 100.00,
        'category' => 'TestCategory',
        'image' => null,
        'is_active' => true,
    ]);

    $this->variant = ProductVariantEloquent::create([
        'product_id' => $this->product->id,
        'sku' => 'TEST-VAR-SKU',
        'price' => 120.00,
    ]);

    // Inventory levels
    InventoryEloquent::create([
        'product_id' => $this->product->id,
        'product_variant_id' => null,
        'stock' => 10,
        'low_stock_threshold' => 3
    ]);

    InventoryEloquent::create([
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
        'stock' => 5,
        'low_stock_threshold' => 2
    ]);

    // Coupon
    $this->coupon = CouponEloquent::create([
        'code' => 'TEST50',
        'type' => 'fixed',
        'value' => 50.00,
        'min_order_amount' => 100.00,
        'expires_at' => now()->addDays(10),
        'usage_limit' => 10,
        'times_used' => 0,
        'is_active' => true,
    ]);
});

test('guest can browse catalog products', function () {
    $response = $this->get('/catalog');
    $response->assertStatus(200);
    $response->assertSee('Test Product');
});

test('user can register and login successfully', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    
    // Logout
    $this->post('/logout')->assertRedirect('/');
    
    // Login
    $response = $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);
    $response->assertRedirect('/');
    $this->assertAuthenticated();
});

test('user can add and update cart items', function () {
    $user = UserEloquent::create([
        'name' => 'Cart Owner',
        'email' => 'cartowner@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $response = $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'variant_id' => $this->variant->id,
        'quantity' => 2,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('cart_items', [
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
        'quantity' => 2,
    ]);

    // Update quantity
    $this->post('/cart/update', [
        'product_id' => $this->product->id,
        'variant_id' => $this->variant->id,
        'quantity' => 4,
    ])->assertRedirect();

    $this->assertDatabaseHas('cart_items', [
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
        'quantity' => 4,
    ]);
});

test('user cannot add items beyond available stock', function () {
    $response = $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'variant_id' => $this->variant->id,
        'quantity' => 10, // Stock is 5
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('cart_items', [
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
    ]);
});

test('user can apply valid coupons', function () {
    $user = UserEloquent::create([
        'name' => 'Coupon User',
        'email' => 'couponuser@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    // Add item to cart to meet minimum amount
    $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'variant_id' => $this->variant->id,
        'quantity' => 1, // Price is $120.00
    ]);

    $response = $this->post('/coupon/apply', [
        'code' => 'TEST50',
        'amount' => 120.00,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(session()->get('applied_coupon.code'))->toBe('TEST50');
    expect(session()->get('applied_coupon.discount'))->toEqual(50.00);
});

test('full checkout flow triggers low stock alerts and processes order', function () {
    // 1. Create a user
    $user = UserEloquent::create([
        'name' => 'Buyer',
        'email' => 'buyer@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    // 2. Add product variant to cart
    $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'variant_id' => $this->variant->id,
        'quantity' => 4, // Stock is 5. Buying 4 drops stock to 1 (which is <= low stock threshold 2)
    ]);

    // 3. Apply coupon
    $this->post('/coupon/apply', [
        'code' => 'TEST50',
        'amount' => 480.00, // 4 * 120
    ]);

    // 4. Place order
    $response = $this->post('/checkout', [
        'billing_name' => 'Buyer Name',
        'billing_email' => 'buyer@example.com',
        'billing_address' => '123 Test Street',
    ]);

    // 5. Assertions
    $response->assertRedirect();
    
    // Order was saved
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'billing_name' => 'Buyer Name',
        'subtotal' => 480.00,
        'discount_amount' => 50.00,
        'total' => 430.00,
    ]);

    // Stock was deducted: 5 - 4 = 1
    $this->assertDatabaseHas('inventories', [
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
        'stock' => 1,
    ]);

    // Low stock alert was generated
    $this->assertDatabaseHas('stock_alerts', [
        'product_id' => $this->product->id,
        'product_variant_id' => $this->variant->id,
        'is_resolved' => false,
    ]);

    // Cart was deleted
    $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);
});

test('user can submit product reviews', function () {
    $user = UserEloquent::create([
        'name' => 'Reviewer',
        'email' => 'reviewer@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $response = $this->post("/products/{$this->product->id}/reviews", [
        'rating' => 5,
        'comment' => 'This is a fantastic product!',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'product_id' => $this->product->id,
        'user_id' => $user->id,
        'rating' => 5,
        'comment' => 'This is a fantastic product!',
    ]);
});
