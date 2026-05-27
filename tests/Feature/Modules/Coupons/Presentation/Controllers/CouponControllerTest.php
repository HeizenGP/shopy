<?php

use App\Modules\Coupons\Infrastructure\Database\CouponModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can validate a fixed value coupon', function () {
    CouponModel::create([
        'code' => 'SAVE10',
        'type' => 'fixed',
        'value' => 10.00,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addDay(),
        'usage_limit' => 10,
        'used_count' => 0,
    ]);

    $response = $this->postJson('/api/coupons/validate', [
        'code' => 'SAVE10',
        'total_amount' => 50.00,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'discount' => 10.00,
            'new_total' => 40.00,
        ]);
});

test('user can validate a percentage value coupon', function () {
    CouponModel::create([
        'code' => 'HALFOFF',
        'type' => 'percentage',
        'value' => 50.00,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addDay(),
        'usage_limit' => 10,
        'used_count' => 0,
    ]);

    $response = $this->postJson('/api/coupons/validate', [
        'code' => 'HALFOFF',
        'total_amount' => 100.00,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'discount' => 50.00,
            'new_total' => 50.00,
        ]);
});

test('validating an invalid coupon returns 422', function () {
    CouponModel::create([
        'code' => 'EXPIRED',
        'type' => 'fixed',
        'value' => 10.00,
        'starts_at' => now()->subDays(5),
        'expires_at' => now()->subDays(2),
        'usage_limit' => 10,
        'used_count' => 0,
    ]);

    $response = $this->postJson('/api/coupons/validate', [
        'code' => 'EXPIRED',
        'total_amount' => 50.00,
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['error' => 'El cupón no es válido o ya caducó.']);
});
