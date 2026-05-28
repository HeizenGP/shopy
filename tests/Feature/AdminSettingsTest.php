<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;
use App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent;
use App\Helpers\ShopHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed system settings (equivalent to migration)
    Artisan::call('migrate');

    // Create a super admin user
    $this->superAdmin = UserEloquent::create([
        'name' => 'Super Admin',
        'email' => 'admin@shop.com',
        'password' => bcrypt('password123'),
        'role' => 'super_admin',
    ]);
});

test('super admin can access settings page and see groups', function () {
    $this->actingAs($this->superAdmin);

    $response = $this->get(route('admin.settings'));

    $response->assertStatus(200);
    $response->assertSee('Información General de la Tienda');
    $response->assertSee('Identidad Visual');
    $response->assertSee('Moneda');
    $response->assertSee('Funcionalidades de la Plataforma');
});

test('super admin can update settings successfully', function () {
    $this->actingAs($this->superAdmin);

    // Get previous value
    $oldName = ShopHelper::getSetting('shop_name');

    $response = $this->post(route('admin.settings.update'), [
        'shop_name' => 'Updated Shop Name Store',
        'shop_email' => 'contact@updated.com',
        'shop_phone' => '+51 987654321',
        'shop_description' => 'Updated Store SEO description',
        'logo_url' => '/images/new-logo.png',
        'favicon_url' => '/new-favicon.ico',
        'banner_url' => '/images/new-banner.jpg',
        'color_theme_admin' => 'custom',
        'color_theme_web' => 'custom',
        'color_client_page' => '#ffffff',
        'color_client_primary' => '#ff0000',
        'color_client_surface' => '#ffffff',
        'color_client_surface_alt' => '#f1f5f9',
        'color_client_border' => '#e2e8f0',
        'color_client_text' => '#0f172a',
        'color_client_muted' => '#64748b',
        'color_client_header_footer_bg' => '#ffffff',
        'color_client_card' => '#ffffff',
        'color_client_card_border' => '#e2e8f0',
        'color_admin_sidebar' => '#111111',
        'color_admin_primary' => '#222222',
        'color_admin_container_bg' => '#ffffff',
        'color_admin_header_bg' => '#ffffff',
        'color_admin_page_bg' => '#f3f4f6',
        'shop_currency' => 'EUR',
        'shop_currency_symbol' => '€',
        'shop_tax_rate' => 18,
        'shipping_free_threshold' => 150,
        'timezone' => 'Europe/Madrid',
        'maintenance_mode' => '1',
        'maintenance_message' => 'Estamos actualizando el servidor.',
        'reviews_moderation' => '0',
        'orders_auto_email' => '0',
        'wishlist_enabled' => '0',
    ]);

    $response->assertRedirect(route('admin.settings'));
    $response->assertSessionHas('success');

    // Sincronización y persistencia en DB
    $this->assertDatabaseHas('settings', [
        'key' => 'shop_name',
        'value' => 'Updated Shop Name Store',
    ]);
    
    $this->assertDatabaseHas('settings', [
        'key' => 'maintenance_mode',
        'value' => '1',
    ]);

    // Check custom color
    $this->assertDatabaseHas('settings', [
        'key' => '--color-client-primary',
        'value' => '#ff0000',
    ]);

    // Check helper returns the new value
    expect(ShopHelper::getSetting('shop_name'))->toBe('Updated Shop Name Store');
});

test('settings cache is flushed when setting is updated', function () {
    $this->actingAs($this->superAdmin);

    // Warm up cache
    $name = ShopHelper::getSetting('shop_name');
    expect(Cache::has(ShopHelper::CACHE_KEY))->toBeTrue();

    // Modify a setting
    SettingEloquent::where('key', 'shop_name')->first()->update([
        'value' => 'Direct DB Edit Store Name',
    ]);

    // Cache must be cleared by booted event
    expect(Cache::has(ShopHelper::CACHE_KEY))->toBeFalse();

    // Warm up cache again and check value
    expect(ShopHelper::getSetting('shop_name'))->toBe('Direct DB Edit Store Name');
    expect(Cache::has(ShopHelper::CACHE_KEY))->toBeTrue();
});

test('config helper overrides values dynamically at runtime', function () {
    $this->actingAs($this->superAdmin);

    // Update settings in database
    SettingEloquent::where('key', 'shop_name')->first()->update([
        'value' => 'Config Override Store',
    ]);

    // Clear cache to simulate a fresh request
    ShopHelper::clearCache();

    // Trigger AppServiceProvider boot logic manually since the app boots before test starts
    (new \App\Providers\AppServiceProvider(app()))->boot();

    expect(config('shop.name'))->toBe('Config Override Store');
    expect(config('app.name'))->toBe('Config Override Store');
});

test('settings export returns correct json response', function () {
    $this->actingAs($this->superAdmin);

    $response = $this->get(route('admin.settings.export'));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/json');
    $response->assertHeader('content-disposition', 'attachment; filename="shopcms_settings_' . date('Y-m-d') . '_' . date('H') . date('i') . date('s') . '.json"'); // match dynamic filename prefix

    $data = json_decode($response->getContent(), true);
    
    expect(is_array($data))->toBeTrue();
    
    // Check if it contains some default keys
    $keys = array_column($data, 'key');
    expect($keys)->toContain('shop_name');
    expect($keys)->toContain('--color-client-primary');
});

test('settings import restores values successfully', function () {
    $this->actingAs($this->superAdmin);

    $jsonData = json_encode([
        [
            'key' => 'shop_name',
            'value' => 'Imported Store Name',
            'description' => 'Nombre de la tienda',
        ],
        [
            'key' => '--color-client-primary',
            'value' => '#ffcc00',
            'description' => 'Color principal de acento',
        ]
    ]);

    $file = UploadedFile::fake()->createWithContent('settings.json', $jsonData);

    $response = $this->post(route('admin.settings.import'), [
        'settings_file' => $file,
    ]);

    $response->assertRedirect(route('admin.settings'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('settings', [
        'key' => 'shop_name',
        'value' => 'Imported Store Name',
    ]);

    $this->assertDatabaseHas('settings', [
        'key' => '--color-client-primary',
        'value' => '#ffcc00',
    ]);
});
