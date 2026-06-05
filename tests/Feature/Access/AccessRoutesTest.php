<?php

use App\Access\Infrastructure\Database\Seeders\AccessSeeder;
use App\Access\Infrastructure\Models\AccessAuditLogModel;
use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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
    $this->seed(AccessSeeder::class);

    $user = UserModel::query()->where('email', 'heizen@shopy.test')->firstOrFail();

    $this->assertDatabaseHas('permissions', ['slug' => 'access.view']);
    $this->assertDatabaseHas('permissions', ['slug' => 'catalog.manage_images']);
    $this->assertDatabaseHas('roles', ['slug' => 'super_admin', 'is_system' => true]);
    expect(Hash::check('heizen123', $user->password))->toBeTrue();
    expect($user->roles()->where('slug', 'super_admin')->exists())->toBeTrue();
});
