<?php

namespace Database\Seeders;

use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $electronics = CategoryModel::query()->firstOrCreate(
            ['slug' => 'electronica'],
            ['name' => 'Electronica', 'description' => 'Dispositivos y accesorios', 'is_active' => true]
        );

        $home = CategoryModel::query()->firstOrCreate(
            ['slug' => 'hogar'],
            ['name' => 'Hogar', 'description' => 'Productos practicos para casa', 'is_active' => true]
        );

        $brand = BrandModel::query()->firstOrCreate(
            ['slug' => 'nova'],
            ['name' => 'Nova', 'description' => 'Marca de demostracion', 'is_active' => true]
        );

        $product = ProductModel::query()->firstOrCreate(
            ['slug' => 'audifonos-nova-air'],
            [
                'brand_id' => $brand->id,
                'main_category_id' => $electronics->id,
                'name' => 'Audifonos Nova Air',
                'sku' => 'NOVA-AIR-001',
                'short_description' => 'Audio inalambrico compacto con carga rapida.',
                'description' => 'Audifonos livianos para uso diario, llamadas y musica con controles tactiles.',
                'regular_price' => 189.90,
                'sale_price' => 149.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => true,
                'published_at' => now(),
            ]
        );

        $product->categories()->syncWithoutDetaching([$electronics->id, $home->id]);
        $product->variants()->firstOrCreate(
            ['sku' => 'NOVA-AIR-BLK'],
            ['name' => 'Negro', 'regular_price' => 189.90, 'sale_price' => 149.90, 'is_default' => true]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
