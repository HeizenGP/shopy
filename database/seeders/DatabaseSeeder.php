<?php

namespace Database\Seeders;

use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
use App\Catalog\Infrastructure\Models\ProductVariantModel;
use App\Catalog\Infrastructure\Models\ProductImageModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key constraints to truncate tables safely across different DB engines
        Schema::disableForeignKeyConstraints();
        DB::table('product_category')->truncate();
        ProductImageModel::truncate();
        ProductVariantModel::truncate();
        ProductModel::truncate();
        CategoryModel::truncate();
        BrandModel::truncate();
        Schema::enableForeignKeyConstraints();

        // Create categories
        $electronics = CategoryModel::create([
            'name' => 'Electrónica',
            'slug' => 'electronica',
            'description' => 'Gadgets, audio y tecnología de punta con stock garantizado',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $home = CategoryModel::create([
            'name' => 'Hogar & Oficina',
            'slug' => 'hogar-oficina',
            'description' => 'Artículos prácticos y minimalistas para optimizar tu espacio de trabajo',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $accessories = CategoryModel::create([
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'description' => 'Mochilas, botellas y complementos perfectos para tu día a día',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create brands
        $nova = BrandModel::create([
            'name' => 'Nova Labs',
            'slug' => 'nova-labs',
            'description' => 'Audio y wearables de alta fidelidad y diseño futurista',
            'is_active' => true,
        ]);

        $apex = BrandModel::create([
            'name' => 'Apex Gear',
            'slug' => 'apex-gear',
            'description' => 'Periféricos premium y tecnología ergonómica avanzada',
            'is_active' => true,
        ]);

        $lumina = BrandModel::create([
            'name' => 'Lumina Co',
            'slug' => 'lumina-co',
            'description' => 'Soluciones de iluminación inteligente y decoración moderna',
            'is_active' => true,
        ]);

        $sleek = BrandModel::create([
            'name' => 'Sleek Design',
            'slug' => 'sleek-design',
            'description' => 'Productos de uso cotidiano diseñados bajo una estética minimalista',
            'is_active' => true,
        ]);

        // Define products array to iterate
        $productsData = [
            [
                'brand_id' => $nova->id,
                'main_category_id' => $electronics->id,
                'name' => 'Audífonos Nova Air ANC',
                'slug' => 'audifonos-nova-air-anc',
                'sku' => 'NOVA-AIR-001',
                'short_description' => 'Cancelación activa de ruido inteligente y sonido de alta fidelidad.',
                'description' => 'Los nuevos Nova Air ANC redefinen el audio inalámbrico de gama premium. Cuentan con cancelación activa de ruido (ANC) híbrida, más de 30 horas de reproducción continua, ecualización inteligente y un estuche de carga inalámbrica rápida. Ideales para el viaje diario, oficina y deportes con resistencia al sudor IPX4.',
                'regular_price' => 199.90,
                'sale_price' => 149.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => true,
                'categories' => [$electronics->id, $accessories->id],
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => [
                    ['name' => 'Negro Mate', 'sku' => 'NOVA-AIR-BLK', 'regular_price' => 199.90, 'sale_price' => 149.90, 'is_default' => true],
                    ['name' => 'Blanco Platino', 'sku' => 'NOVA-AIR-WHT', 'regular_price' => 199.90, 'sale_price' => 159.90, 'is_default' => false],
                    ['name' => 'Azul Medianoche', 'sku' => 'NOVA-AIR-BLU', 'regular_price' => 209.90, 'sale_price' => 169.90, 'is_default' => false]
                ]
            ],
            [
                'brand_id' => $apex->id,
                'main_category_id' => $electronics->id,
                'name' => 'Teclado Mecánico Apex Pro',
                'slug' => 'teclado-mecanico-apex-pro',
                'sku' => 'APEX-PRO-001',
                'short_description' => 'Teclado compacto 75% con switches hot-swappable e iluminación RGB direccionable.',
                'description' => 'El teclado mecánico Apex Pro ofrece la máxima velocidad de respuesta y una sensación de escritura premium. Posee switches táctiles mecánicos pre-lubricados, chasis de aluminio aeronáutico anodizado y conectividad inalámbrica triple (Bluetooth, 2.4GHz y cable USB-C). Todo el poder en un tamaño compacto del 75%.',
                'regular_price' => 349.00,
                'sale_price' => 299.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => true,
                'categories' => [$electronics->id, $home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => [
                    ['name' => 'Interruptores Red Lineales', 'sku' => 'APEX-KEY-RED', 'regular_price' => 349.00, 'sale_price' => 299.90, 'is_default' => true],
                    ['name' => 'Interruptores Brown Táctiles', 'sku' => 'APEX-KEY-BRW', 'regular_price' => 349.00, 'sale_price' => 309.90, 'is_default' => false]
                ]
            ],
            [
                'brand_id' => $apex->id,
                'main_category_id' => $electronics->id,
                'name' => 'Reloj Inteligente Apex Active',
                'slug' => 'reloj-inteligente-apex-active',
                'sku' => 'APEX-ACT-002',
                'short_description' => 'Pantalla AMOLED retina siempre encendida, sensor GPS e informes de salud completos.',
                'description' => 'Lleva tu control de rendimiento diario al siguiente nivel. El Apex Active cuenta con monitoreo cardíaco continuo, SpO2, seguimiento de sueño científico y más de 100 modos deportivos. Con su chasis de policarbonato reforzado y resistencia al agua 5ATM, te acompañará a donde vayas hasta por 14 días por carga.',
                'regular_price' => 249.90,
                'sale_price' => 199.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => false,
                'categories' => [$electronics->id, $accessories->id],
                'images' => [
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $sleek->id,
                'main_category_id' => $accessories->id,
                'name' => 'Mochila Impermeable Sleek Urban',
                'slug' => 'mochila-impermeable-sleek-urban',
                'sku' => 'SLEEK-URB-003',
                'short_description' => 'Tela ecológica repelente al agua, puerto USB integrado y espacio para laptop 16".',
                'description' => 'La mochila definitiva para el trabajo y los viajes diarios. Elaborada con poliéster reciclado de alta resistencia al agua y rasgaduras. Cuenta con bolsillos ocultos de seguridad, correas ergonómicas acolchadas que alivian el peso en los hombros, y un interior inteligente ultra organizado.',
                'regular_price' => 159.00,
                'sale_price' => 129.00,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => false,
                'categories' => [$accessories->id],
                'images' => [
                    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $lumina->id,
                'main_category_id' => $home->id,
                'name' => 'Lámpara de Escritorio Lumina Aura',
                'slug' => 'lampara-de-escritorio-lumina-aura',
                'sku' => 'LUMINA-AUR-004',
                'short_description' => 'Brillo y temperatura regulable con base de carga inalámbrica rápida Qi 15W.',
                'description' => 'Aumenta tu productividad y cuida tu vista. Lumina Aura ofrece 5 tonalidades de color e intensidad regulable mediante panel deslizable táctil. Su brazo articulado te permite posicionarla en cualquier ángulo y su base carga tu smartphone de forma inalámbrica.',
                'regular_price' => 129.90,
                'sale_price' => 89.90,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'has_variants' => false,
                'categories' => [$home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1534073828943-f801091bb18c?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $sleek->id,
                'main_category_id' => $accessories->id,
                'name' => 'Botella Térmica Sleek Flow',
                'slug' => 'botella-termica-sleek-flow',
                'sku' => 'SLEEK-FLW-005',
                'short_description' => 'Aislamiento al vacío de doble pared. Conserva frío por 24h y calor por 12h.',
                'description' => 'La botella Sleek Flow es resistente, ligera y elegante. Hecha con acero inoxidable de grado médico 18/8. No retiene olores ni sabores, tiene un acabado mate texturizado anti-sudor y su tapa hermética a prueba de derrames es perfecta para llevar a la oficina o el gimnasio.',
                'regular_price' => 79.90,
                'sale_price' => 59.90,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'has_variants' => true,
                'categories' => [$accessories->id, $home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&auto=format&fit=crop&q=60',
                    'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => [
                    ['name' => 'Verde Salvia', 'sku' => 'SLEEK-FLW-GRN', 'regular_price' => 79.90, 'sale_price' => 59.90, 'is_default' => true],
                    ['name' => 'Gris Ceniza', 'sku' => 'SLEEK-FLW-GRY', 'regular_price' => 79.90, 'sale_price' => 59.90, 'is_default' => false]
                ]
            ],
            [
                'brand_id' => $lumina->id,
                'main_category_id' => $home->id,
                'name' => 'Soporte Laptop Lumina Flex',
                'slug' => 'soporte-laptop-lumina-flex',
                'sku' => 'LUMINA-FLX-006',
                'short_description' => 'Soporte ergonómico plegable de aluminio para laptops y tablets.',
                'description' => 'Diseño ergonómico plegable y liviano. Mejora la postura corporal elevando la pantalla de tu laptop. Construcción premium en aluminio con almohadillas protectoras de silicona para evitar rayones y aberturas que facilitan la disipación de calor.',
                'regular_price' => 99.90,
                'sale_price' => 79.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => false,
                'categories' => [$home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $apex->id,
                'main_category_id' => $electronics->id,
                'name' => 'Mouse Inalámbrico Apex Move',
                'slug' => 'mouse-inalambrico-apex-move',
                'sku' => 'APEX-MVE-007',
                'short_description' => 'Diseño vertical ergonómico de 57 grados para reducir la fatiga muscular.',
                'description' => 'El ratón inalámbrico Apex Move está diseñado científicamente para adaptarse de forma natural a la mano y evitar el síndrome del túnel carpiano. Posee sensor de alta precisión óptico de hasta 4000 DPI, clics súper silenciosos y batería recargable vía USB-C de larga duración.',
                'regular_price' => 149.90,
                'sale_price' => 119.90,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'has_variants' => false,
                'categories' => [$electronics->id],
                'images' => [
                    'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $sleek->id,
                'main_category_id' => $home->id,
                'name' => 'Organizador de Escritorio Sleek Wood',
                'slug' => 'organizador-de-escritorio-sleek-wood',
                'sku' => 'SLEEK-WOD-008',
                'short_description' => 'Organizador de madera de nogal natural y ranuras para smartphone.',
                'description' => 'Añade calidez y orden a tu escritorio. Fabricado a mano en madera maciza de nogal con acabados de aceites naturales. Dispone de ranura vertical para tablet o celular, bandeja para clips y monedas, y espacio para notas y bolígrafos.',
                'regular_price' => 89.90,
                'sale_price' => 69.90,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'has_variants' => false,
                'categories' => [$home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1595079676339-1534801ad6cf?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $apex->id,
                'main_category_id' => $electronics->id,
                'name' => 'Cargador Solar Apex Power',
                'slug' => 'cargador-solar-apex-power',
                'sku' => 'APEX-SOL-009',
                'short_description' => 'Powerbank solar de 20000mAh a prueba de golpes y agua.',
                'description' => 'El cargador portátil definitivo para tus aventuras al aire libre. Equipado con celdas solares de alta eficiencia, linterna LED doble con función S.O.S., 2 puertos USB de carga rápida y chasis de caucho reforzado anticaídas IP65.',
                'regular_price' => 129.90,
                'sale_price' => 99.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => false,
                'categories' => [$electronics->id, $accessories->id],
                'images' => [
                    'https://images.unsplash.com/photo-1609592424109-dd7715891395?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $lumina->id,
                'main_category_id' => $accessories->id,
                'name' => 'Lentes Anti Luz Azul Lumina Shield',
                'slug' => 'lentes-anti-luz-azul-lumina-shield',
                'sku' => 'LUMINA-SHD-010',
                'short_description' => 'Protección para fatiga ocular con montura ligera y estilizada.',
                'description' => 'Protege tus ojos de las pantallas digitales. Las lunas especiales Lumina Shield bloquean el 99% de la luz azul dañina emitida por laptops y celulares. Montura de policarbonato ultraligero y patillas flexibles para máximo confort.',
                'regular_price' => 69.90,
                'sale_price' => 49.90,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'has_variants' => false,
                'categories' => [$accessories->id, $home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ],
            [
                'brand_id' => $lumina->id,
                'main_category_id' => $home->id,
                'name' => 'Humidificador Ultrasónico Lumina Mist',
                'slug' => 'humidificador-ultrasonico-lumina-mist',
                'sku' => 'LUMINA-MST-011',
                'short_description' => 'Humidificador y difusor de aromas silencioso con luz cálida LED.',
                'description' => 'Crea un ambiente relajante y saludable en casa. Con capacidad de 500ml y apagado automático inteligente sin agua. Su tecnología ultrasónica produce una niebla fría y fina, funcionando silenciosamente por más de 10 horas de difusión.',
                'regular_price' => 119.90,
                'sale_price' => 89.90,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'has_variants' => false,
                'categories' => [$home->id],
                'images' => [
                    'https://images.unsplash.com/photo-1519183071298-a2962feb14f4?w=800&auto=format&fit=crop&q=60'
                ],
                'variants' => []
            ]
        ];

        foreach ($productsData as $item) {
            $p = ProductModel::create([
                'brand_id' => $item['brand_id'],
                'main_category_id' => $item['main_category_id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'sku' => $item['sku'],
                'short_description' => $item['short_description'],
                'description' => $item['description'],
                'regular_price' => $item['regular_price'],
                'sale_price' => $item['sale_price'],
                'status' => $item['status'],
                'is_featured' => $item['is_featured'],
                'has_variants' => $item['has_variants'],
                'published_at' => now(),
            ]);

            $p->categories()->sync($item['categories']);

            foreach ($item['images'] as $idx => $imgUrl) {
                ProductImageModel::create([
                    'product_id' => $p->id,
                    'path' => $imgUrl,
                    'is_main' => $idx === 0,
                    'sort_order' => $idx,
                ]);
            }

            foreach ($item['variants'] as $v) {
                ProductVariantModel::create([
                    'product_id' => $p->id,
                    'name' => $v['name'],
                    'sku' => $v['sku'],
                    'regular_price' => $v['regular_price'],
                    'sale_price' => $v['sale_price'],
                    'is_active' => true,
                    'is_default' => $v['is_default'],
                ]);
            }
        }

        // Ensure admin user exists
        User::query()->firstOrCreate([
            'email' => 'admin@shopcms.com'
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
        ]);
    }
}
