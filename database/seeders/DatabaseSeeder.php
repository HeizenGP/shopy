<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Catalog\Infrastructure\Database\ProductModel;
use App\Modules\Catalog\Infrastructure\Database\ProductVariantModel;
use App\Modules\Coupons\Infrastructure\Database\CouponModel;
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
        // 1. Seed Demo User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Seed Catalog Products
        $product1 = ProductModel::create([
            'name' => 'Zapatillas Premium Run',
            'slug' => 'zapatillas-premium-run',
            'description' => 'Zapatillas deportivas de alto rendimiento con amortiguación avanzada y materiales ultra transpirables para máxima comodidad.',
            'price' => 120.00,
            'status' => 'published',
            'stock' => 50,
        ]);

        ProductVariantModel::create([
            'product_id' => $product1->id,
            'name' => 'Talla 42 / Azul',
            'sku' => 'ZAP-42-AZL',
            'price' => 120.00,
            'stock' => 20,
        ]);

        ProductVariantModel::create([
            'product_id' => $product1->id,
            'name' => 'Talla 43 / Negro',
            'sku' => 'ZAP-43-NGR',
            'price' => 125.00, // Price override
            'stock' => 30,
        ]);

        $product2 = ProductModel::create([
            'name' => 'Polera Minimalista Hoodie',
            'slug' => 'polera-minimalista-hoodie',
            'description' => 'Polera con capucha fabricada en algodón orgánico de alta densidad. Estilo minimalista perfecto para el día a día.',
            'price' => 60.00,
            'status' => 'published',
            'stock' => 100,
        ]);

        ProductVariantModel::create([
            'product_id' => $product2->id,
            'name' => 'Talla M / Gris',
            'sku' => 'POL-M-GRS',
            'price' => null, // Inherits base price (60.00)
            'stock' => 40,
        ]);

        ProductVariantModel::create([
            'product_id' => $product2->id,
            'name' => 'Talla L / Gris',
            'sku' => 'POL-L-GRS',
            'price' => 65.00, // Price override
            'stock' => 60,
        ]);

        ProductModel::create([
            'name' => 'Mochila Impermeable Canvas',
            'slug' => 'mochila-impermeable-canvas',
            'description' => 'Mochila de lona impermeable con compartimiento acolchado para laptop de hasta 15 pulgadas y múltiples bolsillos organizadores.',
            'price' => 45.00,
            'status' => 'published',
            'stock' => 30,
        ]);

        ProductModel::create([
            'name' => 'Lentes de Sol Retro',
            'slug' => 'lentes-de-sol-retro',
            'description' => 'Lentes de sol con protección UV400 y montura de acetato estilo retro. (Borrador, no se muestra en el catálogo público).',
            'price' => 25.00,
            'status' => 'draft',
            'stock' => 10,
        ]);

        // 3. Seed Coupons
        CouponModel::create([
            'code' => 'SAVE10',
            'type' => 'fixed',
            'value' => 10.00,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => 500,
            'used_count' => 0,
        ]);

        CouponModel::create([
            'code' => 'HALFOFF',
            'type' => 'percentage',
            'value' => 50.00,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => 200,
            'used_count' => 0,
        ]);
    }
}
