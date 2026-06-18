<?php

use App\Access\Infrastructure\Database\Seeders\AccessSeeder;
use App\Access\Infrastructure\Models\PermissionModel;
use App\Access\Infrastructure\Models\RoleModel;
use App\Access\Infrastructure\Models\UserModel;
use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
use App\Catalog\Infrastructure\Models\ProductVariantModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function catalogPermissionUser(array $permissionSlugs): UserModel
{
    $role = RoleModel::query()->create([
        'name' => 'Catalog Test Role',
        'slug' => 'catalog_test_role_'.str_replace('.', '_', implode('_', $permissionSlugs)),
    ]);

    foreach ($permissionSlugs as $permissionSlug) {
        [$module] = explode('.', $permissionSlug, 2);

        $permission = PermissionModel::query()->firstOrCreate(
            ['slug' => $permissionSlug],
            [
                'name' => $permissionSlug,
                'module' => $module,
            ]
        );

        $role->permissions()->syncWithoutDetaching([$permission->id]);
    }

    $user = UserModel::query()->create([
        'name' => 'Catalog Admin',
        'email' => 'catalog-admin-'.md5(implode('|', $permissionSlugs)).'@shopy.test',
        'password' => 'password12345',
        'is_active' => true,
    ]);
    $user->roles()->attach($role);

    return $user;
}

function catalogSuperAdmin(): UserModel
{
    $role = RoleModel::query()->create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'is_system' => true,
    ]);

    return catalogUserForRole($role, 'super-admin@shopy.test');
}

function catalogUserForRole(RoleModel $role, string $email): UserModel
{
    $user = UserModel::query()->create([
        'name' => 'Catalog Admin',
        'email' => $email,
        'password' => 'password12345',
        'is_active' => true,
    ]);
    $user->roles()->attach($role);

    return $user;
}

function catalogProductFixture(): ProductModel
{
    return ProductModel::query()->create([
        'name' => 'Polo Basic',
        'slug' => 'polo-basic',
        'sku' => 'POLO-001',
        'regular_price' => 49.9,
        'status' => ProductStatus::Draft,
    ]);
}

it('renders the public catalog routes', function (): void {
    $category = CategoryModel::query()->create([
        'name' => 'Electronica',
        'slug' => 'electronica',
        'is_active' => true,
    ]);

    $brand = BrandModel::query()->create([
        'name' => 'Nova',
        'slug' => 'nova',
        'is_active' => true,
    ]);

    $product = ProductModel::query()->create([
        'brand_id' => $brand->id,
        'main_category_id' => $category->id,
        'name' => 'Audifonos Nova Air',
        'slug' => 'audifonos-nova-air',
        'sku' => 'NOVA-AIR-001',
        'regular_price' => 189.90,
        'sale_price' => 149.90,
        'status' => ProductStatus::Published,
        'is_featured' => true,
        'published_at' => now(),
    ]);

    $product->categories()->attach($category);

    $this->get('/')->assertOk()->assertSee('Audifonos Nova Air');
    $this->get('/products')->assertOk()->assertSee('Audifonos Nova Air');
    $this->get('/products/audifonos-nova-air')->assertOk()->assertSee('NOVA-AIR-001');
    $this->get('/categories/electronica')->assertOk()->assertSee('Audifonos Nova Air');
});

it('creates a product from the admin catalog', function (): void {
    $user = catalogPermissionUser(['catalog.manage_products']);

    $category = CategoryModel::query()->create([
        'name' => 'Hogar',
        'slug' => 'hogar',
        'is_active' => true,
    ]);

    $this->actingAs($user)->post('/admin/catalog/products', [
        'name' => 'Lampara Desk',
        'slug' => 'lampara-desk',
        'sku' => 'LAMP-001',
        'regular_price' => 79.9,
        'status' => ProductStatus::Draft->value,
        'main_category_id' => $category->id,
        'category_ids' => [$category->id],
    ])->assertRedirect();

    $this->assertDatabaseHas('products', [
        'slug' => 'lampara-desk',
        'sku' => 'LAMP-001',
    ]);
});

it('updates a product while keeping its existing variant sku', function (): void {
    $user = catalogPermissionUser(['catalog.manage_products']);
    $product = catalogProductFixture();

    ProductVariantModel::query()->create([
        'product_id' => $product->id,
        'name' => 'Talla M',
        'sku' => 'SKU-M-BLU',
        'regular_price' => 49.9,
    ]);

    $this->actingAs($user)->put("/admin/catalog/products/{$product->id}", [
        'name' => 'Polo Basic Actualizado',
        'slug' => 'polo-basic',
        'sku' => 'POLO-001',
        'regular_price' => 49.9,
        'status' => ProductStatus::Draft->value,
        'has_variants' => '1',
        'variants' => [
            [
                'name' => 'Talla M',
                'sku' => 'SKU-M-BLU',
                'regular_price' => 49.9,
            ],
        ],
    ])->assertRedirect();

    $this->assertDatabaseHas('product_variants', [
        'product_id' => $product->id,
        'sku' => 'SKU-M-BLU',
        'deleted_at' => null,
    ]);
});

it('allows only three category levels', function (): void {
    $user = catalogPermissionUser(['catalog.manage_categories']);

    $parent = CategoryModel::query()->create([
        'name' => 'Ropa',
        'slug' => 'ropa',
        'is_active' => true,
    ]);

    $subcategory = CategoryModel::query()->create([
        'parent_id' => $parent->id,
        'name' => 'Hombre',
        'slug' => 'hombre',
        'is_active' => true,
    ]);

    $subsubcategory = CategoryModel::query()->create([
        'parent_id' => $subcategory->id,
        'name' => 'Polos',
        'slug' => 'polos',
        'is_active' => true,
    ]);

    $this->actingAs($user)->post('/admin/catalog/categories', [
        'parent_id' => $subcategory->id,
        'name' => 'Camisas',
        'slug' => 'camisas',
        'is_active' => '1',
    ])->assertRedirect('/admin/catalog/categories');

    $this->actingAs($user)->post('/admin/catalog/categories', [
        'parent_id' => $subsubcategory->id,
        'name' => 'Manga corta',
        'slug' => 'manga-corta',
        'is_active' => '1',
    ])->assertSessionHasErrors('parent_id');

    $this->assertDatabaseMissing('categories', [
        'slug' => 'manga-corta',
    ]);
});

it('redirects guests away from the admin catalog', function (): void {
    $this->get('/admin/catalog/products')
        ->assertRedirect('/admin/login');
});

it('returns 403 when a user lacks catalog products permission', function (): void {
    $user = catalogPermissionUser(['dashboard.view']);

    $this->actingAs($user)
        ->get('/admin/catalog/products')
        ->assertForbidden();
});

it('allows a user with catalog manage products permission to browse products', function (): void {
    $user = catalogPermissionUser(['catalog.manage_products']);

    $this->actingAs($user)
        ->get('/admin/catalog/products')
        ->assertOk()
        ->assertSee('Nuevo Producto');
});

it('allows a user with catalog manage products permission to access product creation', function (): void {
    $user = catalogPermissionUser(['catalog.manage_products']);

    $this->actingAs($user)
        ->get('/admin/catalog/products/create')
        ->assertOk();
});

it('blocks product creation when catalog manage products permission is missing', function (): void {
    $user = catalogPermissionUser(['catalog.manage_categories']);

    $this->actingAs($user)
        ->get('/admin/catalog/products/create')
        ->assertForbidden();
});

it('allows a user with catalog manage products permission to access product editing', function (): void {
    $user = catalogPermissionUser(['catalog.manage_products']);
    $product = catalogProductFixture();

    $this->actingAs($user)
        ->get("/admin/catalog/products/{$product->id}/edit")
        ->assertOk();
});

it('allows catalog managers to edit categories brands and variants from dedicated forms', function (): void {
    $user = catalogPermissionUser([
        'catalog.manage_categories',
        'catalog.manage_brands',
        'catalog.manage_variants',
    ]);

    $category = CategoryModel::query()->create([
        'name' => 'Accesorios',
        'slug' => 'accesorios',
        'is_active' => true,
    ]);

    $brand = BrandModel::query()->create([
        'name' => 'Nova',
        'slug' => 'nova',
        'is_active' => true,
    ]);

    $product = ProductModel::query()->create([
        'brand_id' => $brand->id,
        'main_category_id' => $category->id,
        'name' => 'Audifonos Nova',
        'slug' => 'audifonos-nova',
        'sku' => 'NOVA-001',
        'regular_price' => 129.90,
        'status' => ProductStatus::Draft,
    ]);

    $variant = ProductVariantModel::query()->create([
        'product_id' => $product->id,
        'name' => 'Negro',
        'sku' => 'NOVA-NEGRO',
        'regular_price' => 129.90,
        'is_active' => true,
    ]);

    $this->actingAs($user)->get("/admin/catalog/categories/{$category->id}/edit")
        ->assertOk()
        ->assertSee('Editar Categoría');
    $this->actingAs($user)->get("/admin/catalog/brands/{$brand->id}/edit")
        ->assertOk()
        ->assertSee('Editar Marca');
    $this->actingAs($user)->get("/admin/catalog/variants/{$variant->id}/edit")
        ->assertOk()
        ->assertSee('Editar Variante');

    $this->actingAs($user)->put("/admin/catalog/categories/{$category->id}", [
        'name' => 'Accesorios Premium',
        'slug' => 'accesorios-premium',
        'sort_order' => 2,
        'is_active' => '0',
    ])->assertRedirect("/admin/catalog/categories/{$category->id}/edit");

    $this->actingAs($user)->put("/admin/catalog/brands/{$brand->id}", [
        'name' => 'Nova Labs',
        'slug' => 'nova-labs',
        'is_active' => '0',
    ])->assertRedirect("/admin/catalog/brands/{$brand->id}/edit");

    $this->actingAs($user)->put("/admin/catalog/variants/{$variant->id}", [
        'product_id' => $product->id,
        'name' => 'Negro Mate',
        'sku' => 'NOVA-NEGRO',
        'regular_price' => 139.90,
        'is_active' => '0',
        'is_default' => '1',
    ])->assertRedirect("/admin/catalog/variants/{$variant->id}/edit");

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Accesorios Premium',
        'is_active' => false,
    ]);
    $this->assertDatabaseHas('brands', [
        'id' => $brand->id,
        'name' => 'Nova Labs',
        'is_active' => false,
    ]);
    $this->assertDatabaseHas('product_variants', [
        'id' => $variant->id,
        'name' => 'Negro Mate',
        'is_active' => false,
        'is_default' => true,
    ]);
});

it('blocks product deletion when catalog manage products permission is missing', function (): void {
    $user = catalogPermissionUser(['catalog.manage_categories']);
    $product = catalogProductFixture();

    $this->actingAs($user)
        ->delete("/admin/catalog/products/{$product->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
    ]);
});

it('allows catalog manager to access catalog routes according to seeded permissions', function (): void {
    $this->seed(AccessSeeder::class);

    $role = RoleModel::query()->where('slug', 'catalog_manager')->firstOrFail();
    $user = catalogUserForRole($role, 'catalog-manager@shopy.test');

    $this->actingAs($user)->get('/admin/catalog/products')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/products/create')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/categories')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/brands')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/variants')->assertOk();
});

it('allows super admin to access all catalog administration routes', function (): void {
    $user = catalogSuperAdmin();
    $product = catalogProductFixture();

    $this->actingAs($user)->get('/admin/catalog/products')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/products/create')->assertOk();
    $this->actingAs($user)->get("/admin/catalog/products/{$product->id}/edit")->assertOk();
    $this->actingAs($user)->get('/admin/catalog/categories')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/brands')->assertOk();
    $this->actingAs($user)->get('/admin/catalog/variants')->assertOk();
});

it('keeps public cart and checkout routes available without login', function (): void {
    $this->get('/cart')->assertOk();
    $this->get('/checkout')->assertOk();
});
