<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can register successfully', function () {
    $response = $this->postJson('/api/users/register', [
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'alice@example.com',
    ]);
});

test('user cannot register with existing email', function () {
    User::factory()->create([
        'email' => 'alice@example.com',
    ]);

    $response = $this->postJson('/api/users/register', [
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment([
            'message' => 'El correo electrónico ya está registrado.',
        ]);
});

test('authenticated user can fetch profile', function () {
    $user = User::factory()->create([
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
    ]);

    $response = $this->actingAs($user)->getJson('/api/users/profile');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
        ]);
});
