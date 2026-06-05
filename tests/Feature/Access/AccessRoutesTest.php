<?php

use App\Access\Infrastructure\Database\Seeders\AccessSeeder;
use App\Access\Infrastructure\Models\AccessAuditLogModel;
use App\Access\Infrastructure\Models\PermissionModel;
use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createAccessUser(?RoleModel $role = null, bool $isActive = true, string $email = 'admin@shopy.test'): UserModel
{
    $user = UserModel::query()->create([
        'name' => 'Admin User',
        'email' => $email,
        'password' => 'password12345',
        'is_active' => $isActive,
    ]);

    if ($role) {
        $user->roles()->attach($role);
    }

    return $user;
}

function createAccessRoleWithPermission(string $permissionSlug): RoleModel
{
    [$module] = explode('.', $permissionSlug, 2);

    $permission = PermissionModel::query()->create([
        'name' => $permissionSlug,
        'slug' => $permissionSlug,
        'module' => $module,
    ]);

    $role = RoleModel::query()->create([
        'name' => 'Permitted Role',
        'slug' => Str::slug($permissionSlug, '_'),
    ]);
    $role->permissions()->attach($permission);

    return $role;
}

it('logs in an active administrative user and records audit data', function (): void {
    $role = RoleModel::query()->create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'is_system' => true,
    ]);
    $user = createAccessUser($role);

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'password12345',
    ])->assertRedirect('/admin/dashboard');

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $user->id,
        'event' => 'login_success',
    ]);
    expect($user->fresh()->last_login_at)->not->toBeNull();
});

it('uses a generic message for failed login attempts', function (): void {
    $user = createAccessUser(email: 'failed-login@shopy.test');

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors([
        'email' => 'Credenciales incorrectas.',
    ]);

    $this->assertGuest();
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $user->id,
        'event' => 'login_failed',
    ]);
});

it('rate limits repeated failed login attempts', function (): void {
    $email = 'rate-limit@shopy.test';
    $key = Str::transliterate(Str::lower($email).'|127.0.0.1');
    RateLimiter::clear($key);

    foreach (range(1, 5) as $attempt) {
        $this->post('/admin/login', [
            'email' => $email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    expect(RateLimiter::tooManyAttempts($key, 5))->toBeTrue();

    $this->post('/admin/login', [
        'email' => $email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');
});

it('logs out the authenticated user and records audit data', function (): void {
    $role = RoleModel::query()->create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'is_system' => true,
    ]);
    $user = createAccessUser($role, email: 'logout@shopy.test');

    $this->actingAs($user)
        ->post('/admin/logout')
        ->assertRedirect('/admin/login');

    $this->assertGuest();
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $user->id,
        'event' => 'logout',
    ]);
});

it('rejects inactive users with a generic login error', function (): void {
    $user = createAccessUser(isActive: false);

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'password12345',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $user->id,
        'event' => 'login_failed',
    ]);
});

it('denies access by default when the user lacks explicit permissions', function (): void {
    $role = RoleModel::query()->create([
        'name' => 'Empty Role',
        'slug' => 'empty_role',
    ]);
    $user = createAccessUser($role);

    $this->actingAs($user)->get('/admin/access/users')->assertForbidden();
});

it('allows a user with explicit dashboard permission to reach the dashboard', function (): void {
    $role = createAccessRoleWithPermission('dashboard.view');
    $user = createAccessUser($role, email: 'dashboard-view@shopy.test');

    $this->actingAs($user)->get('/admin/dashboard')->assertOk();
});

it('redirects a catalog-only user to their first permitted module after login', function (): void {
    $role = createAccessRoleWithPermission('catalog.manage_categories');
    $user = createAccessUser($role, email: 'categories-only@shopy.test');

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'password12345',
    ])->assertRedirect('/admin/catalog/categories');
});

it('hides admin links when the user lacks those permissions', function (): void {
    $role = createAccessRoleWithPermission('catalog.manage_categories');
    $user = createAccessUser($role, email: 'category-sidebar@shopy.test');

    $this->actingAs($user)
        ->get('/admin/catalog/categories')
        ->assertOk()
        ->assertSee('/admin/catalog/categories', false)
        ->assertDontSee('/admin/dashboard', false)
        ->assertDontSee('/admin/access/users', false)
        ->assertDontSee('/admin/catalog/products', false)
        ->assertDontSee('/admin/catalog/brands', false)
        ->assertDontSee('/admin/catalog/variants', false);
});

it('allows super admin to browse access users', function (): void {
    $role = RoleModel::query()->create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'is_system' => true,
    ]);
    $user = createAccessUser($role);

    $this->actingAs($user)->get('/admin/access/users')->assertOk()->assertSee('Admin User');
});

it('creates users through the access admin and records an audit event', function (): void {
    $superAdmin = RoleModel::query()->create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'is_system' => true,
    ]);
    $catalogManager = RoleModel::query()->create([
        'name' => 'Catalog Manager',
        'slug' => 'catalog_manager',
    ]);
    $actor = createAccessUser($superAdmin);

    $this->actingAs($actor)->post('/admin/access/users', [
        'name' => 'Catalog User',
        'email' => 'catalog@shopy.test',
        'password' => 'password12345',
        'password_confirmation' => 'password12345',
        'is_active' => '1',
        'role_ids' => [$catalogManager->id],
    ])->assertRedirect('/admin/access/users');

    $createdUser = UserModel::query()->where('email', 'catalog@shopy.test')->firstOrFail();

    $this->assertDatabaseHas('role_user', [
        'user_id' => $createdUser->id,
        'role_id' => $catalogManager->id,
    ]);
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $actor->id,
        'event' => 'user_created',
    ]);
    expect(AccessAuditLogModel::query()->where('event', 'user_created')->first()?->metadata)
        ->toMatchArray(['email' => 'catalog@shopy.test']);
});

it('seeds initial access roles permissions and super admin user', function (): void {
    foreach ([
        'access.view',
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
        'roles.assign_permissions',
        'permissions.view',
        'catalog.view',
        'catalog.create',
        'catalog.update',
        'catalog.delete',
        'catalog.manage_images',
        'orders.view',
    ] as $slug) {
        [$module] = explode('.', $slug, 2);

        PermissionModel::query()->create([
            'name' => $slug,
            'slug' => $slug,
            'module' => $module,
        ]);
    }

    $this->seed(AccessSeeder::class);

    $user = UserModel::query()->where('email', 'admin@shopy.test')->firstOrFail();
    $permissionSlugs = PermissionModel::query()
        ->orderBy('slug')
        ->pluck('slug')
        ->all();

    $this->assertDatabaseHas('permissions', ['name' => 'Ver dashboard', 'slug' => 'dashboard.view']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar usuarios', 'slug' => 'access.manage_users']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar roles', 'slug' => 'access.manage_roles']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar permisos', 'slug' => 'access.manage_permissions']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar productos', 'slug' => 'catalog.manage_products']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar categorías', 'slug' => 'catalog.manage_categories']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar marcas', 'slug' => 'catalog.manage_brands']);
    $this->assertDatabaseHas('permissions', ['name' => 'Gestionar variantes', 'slug' => 'catalog.manage_variants']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'access.view']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'catalog.view']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'catalog.create']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'catalog.update']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'catalog.delete']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'catalog.manage_images']);
    $this->assertDatabaseMissing('permissions', ['slug' => 'orders.view']);
    expect($permissionSlugs)->toBe([
        'access.manage_permissions',
        'access.manage_roles',
        'access.manage_users',
        'catalog.manage_brands',
        'catalog.manage_categories',
        'catalog.manage_products',
        'catalog.manage_variants',
        'dashboard.view',
    ]);
    $this->assertDatabaseHas('roles', ['slug' => 'super_admin', 'is_system' => true]);
    expect(Hash::check('AdminShopy2026!', $user->password))->toBeTrue();
    expect($user->getRawOriginal('password'))->not->toBe('AdminShopy2026!');
    expect($user->roles()->where('slug', 'super_admin')->exists())->toBeTrue();
});
