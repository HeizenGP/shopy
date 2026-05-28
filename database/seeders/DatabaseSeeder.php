<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductVariantEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\VariantOptionEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent;
use App\Modules\Coupons\Infrastructure\Database\Models\CouponEloquent;
use App\Modules\Reviews\Infrastructure\Database\Models\ReviewEloquent;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@shopcms.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ventas@shopcms.com'],
            [
                'name' => 'Ventas Admin',
                'password' => Hash::make('ventas123'),
                'role' => 'sales_admin',
            ]
        );

        // Customer
        $customer = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 2. Catalog Products & Variants
        // Product 1: Casaca de Cuero (with size variants)
        $p1 = ProductEloquent::updateOrCreate(
            ['slug' => 'casaca-cuero-premium'],
            [
                'name' => 'Casaca de Cuero Premium',
                'description' => 'Casaca confeccionada con 100% cuero genuino de alta durabilidad. Diseño moderno de corte ajustado y cierres metálicos robustos. Ideal para climas templados o fríos.',
                'price' => 199.99,
                'category' => 'Moda',
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ]
        );

        $v1_s = ProductVariantEloquent::updateOrCreate(['sku' => 'CUERO-PREM-S'], ['product_id' => $p1->id, 'price' => 199.99]);
        VariantOptionEloquent::updateOrCreate(['product_variant_id' => $v1_s->id, 'name' => 'Talla'], ['value' => 'S']);

        $v1_m = ProductVariantEloquent::updateOrCreate(['sku' => 'CUERO-PREM-M'], ['product_id' => $p1->id, 'price' => 209.99]);
        VariantOptionEloquent::updateOrCreate(['product_variant_id' => $v1_m->id, 'name' => 'Talla'], ['value' => 'M']);

        $v1_l = ProductVariantEloquent::updateOrCreate(['sku' => 'CUERO-PREM-L'], ['product_id' => $p1->id, 'price' => 219.99]);
        VariantOptionEloquent::updateOrCreate(['product_variant_id' => $v1_l->id, 'name' => 'Talla'], ['value' => 'L']);


        // Product 2: Polo de Algodón (with color variants)
        $p2 = ProductEloquent::updateOrCreate(
            ['slug' => 'polo-algodon-minimalista'],
            [
                'name' => 'Polo de Algodón Minimalista',
                'description' => 'Polo básico de cuello redondo confeccionado con algodón Pima peruano extra suave. Alta transpirabilidad y comodidad absoluta.',
                'price' => 29.99,
                'category' => 'Moda',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ]
        );

        $v2_w = ProductVariantEloquent::updateOrCreate(['sku' => 'POLO-MIN-BLANCO'], ['product_id' => $p2->id, 'price' => 29.99]);
        VariantOptionEloquent::updateOrCreate(['product_variant_id' => $v2_w->id, 'name' => 'Color'], ['value' => 'Blanco']);

        $v2_b = ProductVariantEloquent::updateOrCreate(['sku' => 'POLO-MIN-NEGRO'], ['product_id' => $p2->id, 'price' => 31.99]);
        VariantOptionEloquent::updateOrCreate(['product_variant_id' => $v2_b->id, 'name' => 'Color'], ['value' => 'Negro']);


        // Product 3: Audífonos de Cancelación de Ruido (no variants)
        $p3 = ProductEloquent::updateOrCreate(
            ['slug' => 'audifonos-cancelacion-ruido'],
            [
                'name' => 'Audífonos Noise-Cancelling Pro',
                'description' => 'Disfruta de tu música sin distracciones externas con la cancelación activa de ruido híbrida de 40dB. Conexión Bluetooth 5.3 ultra estable y batería de hasta 50 horas de reproducción continua.',
                'price' => 149.99,
                'category' => 'Electrónica',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ]
        );


        // Product 4: Silla de Oficina Ergonómica (no variants)
        $p4 = ProductEloquent::updateOrCreate(
            ['slug' => 'silla-oficina-ergonomica'],
            [
                'name' => 'Silla de Oficina Ergonómica',
                'description' => 'Silla ergonómica para oficina con soporte lumbar ajustable en altura y profundidad. Malla antitranspirante de alta densidad y reposabrazos 3D regulables para cuidar tu postura.',
                'price' => 249.99,
                'category' => 'Hogar',
                'image' => 'https://images.unsplash.com/photo-1505797149-43b0069ec26b?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ]
        );


        // 3. Inventory Levels
        // Casaca Cuero Variants
        InventoryEloquent::updateOrCreate(['product_id' => $p1->id, 'product_variant_id' => $v1_s->id], ['stock' => 12, 'low_stock_threshold' => 3]);
        InventoryEloquent::updateOrCreate(['product_id' => $p1->id, 'product_variant_id' => $v1_m->id], ['stock' => 2, 'low_stock_threshold' => 3]); // Starts low on stock
        InventoryEloquent::updateOrCreate(['product_id' => $p1->id, 'product_variant_id' => $v1_l->id], ['stock' => 15, 'low_stock_threshold' => 3]);

        // Polo Cotton Variants
        InventoryEloquent::updateOrCreate(['product_id' => $p2->id, 'product_variant_id' => $v2_w->id], ['stock' => 50, 'low_stock_threshold' => 5]);
        InventoryEloquent::updateOrCreate(['product_id' => $p2->id, 'product_variant_id' => $v2_b->id], ['stock' => 0, 'low_stock_threshold' => 5]); // Starts Out of Stock

        // Audífonos Base
        InventoryEloquent::updateOrCreate(['product_id' => $p3->id, 'product_variant_id' => null], ['stock' => 8, 'low_stock_threshold' => 4]);

        // Silla Oficina Base
        InventoryEloquent::updateOrCreate(['product_id' => $p4->id, 'product_variant_id' => null], ['stock' => 3, 'low_stock_threshold' => 2]);


        // 4. Coupons
        CouponEloquent::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percentage',
                'value' => 10.00,
                'min_order_amount' => 0.00,
                'expires_at' => now()->addDays(30),
                'usage_limit' => 500,
                'times_used' => 0,
                'is_active' => true,
            ]
        );

        CouponEloquent::updateOrCreate(
            ['code' => 'SAVE50'],
            [
                'type' => 'fixed',
                'value' => 50.00,
                'min_order_amount' => 150.00,
                'expires_at' => now()->addDays(15),
                'usage_limit' => 100,
                'times_used' => 0,
                'is_active' => true,
            ]
        );


        // 5. Reviews
        ReviewEloquent::updateOrCreate(
            ['product_id' => $p1->id, 'user_id' => $customer->id],
            [
                'name' => 'Juan Pérez',
                'rating' => 5,
                'comment' => 'Increíble calidad. El cuero se siente grueso y los cierres son súper suaves. Vale cada centavo.',
            ]
        );

        ReviewEloquent::updateOrCreate(
            ['product_id' => $p1->id, 'name' => 'Clara M.'],
            [
                'rating' => 4,
                'comment' => 'El corte es un poco pequeño, les sugiero comprar una talla más de la habitual. Fuera de eso, excelente.',
            ]
        );

        ReviewEloquent::updateOrCreate(
            ['product_id' => $p3->id, 'name' => 'Carlos D.'],
            [
                'rating' => 5,
                'comment' => 'La cancelación de ruido en la oficina es fantástica. Aísla completamente el sonido del teclado y las charlas.',
            ]
        );
    }
}
