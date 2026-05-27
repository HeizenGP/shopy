<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

// --- API TESTS ---

test('user can login with valid credentials via API', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('secret-password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'secret-password',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'user' => [
                'id' => $user->id,
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
        ]);

    $this->assertAuthenticatedAs($user);
});

test('user cannot login with invalid password via API', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret-password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Credenciales de inicio de sesión inválidas.',
        ]);

    $this->assertGuest();
});

test('user cannot login with non-existent email via API', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'some-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Credenciales de inicio de sesión inválidas.',
        ]);

    $this->assertGuest();
});

test('login requires email and password validation via API', function () {
    $response = $this->postJson('/api/auth/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('user can logout via API', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.',
        ]);

    $this->assertGuest();
});

test('logout via API is protected by auth middleware', function () {
    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(401);
});

// --- WEB TESTS ---

test('login page is accessible to guest', function () {
    $response = $this->get('/login');

    $response->assertStatus(200)
        ->assertViewIs('auth.login');
});

test('authenticated user is redirected from login page to dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect('/dashboard');
});

test('guest is redirected from dashboard to login page', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('user can login via web form with valid credentials', function () {
    $user = User::factory()->create([
        'name' => 'Web Admin',
        'email' => 'webadmin@example.com',
        'password' => Hash::make('my-secret-password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'webadmin@example.com',
        'password' => 'my-secret-password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('user cannot login via web form with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'webadmin@example.com',
        'password' => Hash::make('my-secret-password'),
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => 'webadmin@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('user can logout via web logout route', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});
