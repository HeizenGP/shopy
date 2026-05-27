<?php

use App\Modules\Orders\Infrastructure\Database\OrderModel;
use App\Modules\Payments\Infrastructure\Database\PaymentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can process payment successfully', function () {
    $order = OrderModel::create([
        'status' => 'pending',
        'total_amount' => 50.00,
        'discount_amount' => 0.00,
        'shipping_address' => '123 Main St',
        'billing_address' => '123 Main St',
        'customer_name' => 'Jane Doe',
        'customer_email' => 'jane@example.com',
    ]);

    $response = $this->postJson('/api/payments/process', [
        'order_id' => $order->id,
        'gateway' => 'paypal',
        'payment_token' => 'valid-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'success' => true,
        ]);

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'status' => 'completed',
    ]);

    $this->assertEquals('paid', $order->fresh()->status);
});

test('payment fails with invalid token', function () {
    $order = OrderModel::create([
        'status' => 'pending',
        'total_amount' => 50.00,
        'discount_amount' => 0.00,
        'shipping_address' => '123 Main St',
        'billing_address' => '123 Main St',
        'customer_name' => 'Jane Doe',
        'customer_email' => 'jane@example.com',
    ]);

    $response = $this->postJson('/api/payments/process', [
        'order_id' => $order->id,
        'gateway' => 'paypal',
        'payment_token' => 'invalid-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'success' => false,
        ]);

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'status' => 'failed',
    ]);

    $this->assertEquals('pending', $order->fresh()->status);
});
