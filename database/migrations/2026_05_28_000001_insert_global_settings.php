<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SETTINGS = [
        // Tienda
        [
            'key' => 'shop_name',
            'value' => 'ShopCMS',
            'description' => 'Nombre de la tienda',
        ],
        [
            'key' => 'shop_email',
            'value' => 'info@shop.com',
            'description' => 'Email de contacto',
        ],
        [
            'key' => 'shop_phone',
            'value' => '+1 234 567 890',
            'description' => 'Teléfono de la tienda',
        ],
        [
            'key' => 'shop_description',
            'value' => 'Tu tienda virtual premium',
            'description' => 'Descripción de la tienda (SEO)',
        ],
        [
            'key' => 'shop_currency',
            'value' => 'USD',
            'description' => 'Moneda de la tienda',
        ],
        [
            'key' => 'shop_currency_symbol',
            'value' => '$',
            'description' => 'Símbolo de la moneda',
        ],
        [
            'key' => 'shop_tax_rate',
            'value' => '16',
            'description' => 'Porcentaje de impuesto',
        ],
        [
            'key' => 'shipping_free_threshold',
            'value' => '100',
            'description' => 'Límite de envío gratis',
        ],
        [
            'key' => 'timezone',
            'value' => 'America/New_York',
            'description' => 'Zona horaria de la tienda',
        ],

        // Branding
        [
            'key' => 'logo_url',
            'value' => '/images/logo.png',
            'description' => 'URL del logo de la tienda',
        ],
        [
            'key' => 'favicon_url',
            'value' => '/favicon.ico',
            'description' => 'URL del favicon',
        ],
        [
            'key' => 'banner_url',
            'value' => '/images/banner.jpg',
            'description' => 'URL del banner principal',
        ],

        // Colores Cliente (updateOrInsert to not duplicate if already in previous migration)
        [
            'key' => '--color-client-page',
            'value' => '#f8fafc',
            'description' => 'Fondo general del frontend cliente.',
        ],
        [
            'key' => '--color-client-primary',
            'value' => '#4f46e5',
            'description' => 'Color principal de botones, enlaces y textos importantes.',
        ],
        [
            'key' => '--color-client-login-bg',
            'value' => '#eef2ff',
            'description' => 'Fondo exclusivo de la pantalla de login del cliente.',
        ],
        [
            'key' => '--color-client-surface',
            'value' => '#ffffff',
            'description' => 'Superficie principal del frontend cliente: header, tarjetas y paneles base.',
        ],
        [
            'key' => '--color-client-surface-alt',
            'value' => '#f1f5f9',
            'description' => 'Superficie alternativa para bloques suaves, badges y fondos secundarios.',
        ],
        [
            'key' => '--color-client-border',
            'value' => '#e2e8f0',
            'description' => 'Color de bordes, separadores y contenedores de formulario.',
        ],
        [
            'key' => '--color-client-text',
            'value' => '#0f172a',
            'description' => 'Color principal del texto del cliente: títulos, contenido y navegación.',
        ],
        [
            'key' => '--color-client-muted',
            'value' => '#64748b',
            'description' => 'Color de texto secundario, ayuda contextual y etiquetas suaves.',
        ],
        [
            'key' => '--color-client-header-bg',
            'value' => '#ffffff',
            'description' => 'Fondo del encabezado superior del frontend.',
        ],
        [
            'key' => '--color-client-footer-bg',
            'value' => '#ffffff',
            'description' => 'Fondo del pie de página del frontend.',
        ],
        [
            'key' => '--color-client-card',
            'value' => '#ffffff',
            'description' => 'Fondo de tarjetas, listas y cajas de contenido.',
        ],
        [
            'key' => '--color-client-card-border',
            'value' => '#e2e8f0',
            'description' => 'Borde de tarjetas, cajas destacadas y paneles.',
        ],

        // Admin Colors
        [
            'key' => '--color-admin-sidebar',
            'value' => '#202123',
            'description' => 'Fondo sidebar admin',
        ],
        [
            'key' => '--color-admin-primary',
            'value' => '#4f46e5',
            'description' => 'Color primario admin',
        ],
        [
            'key' => '--color-admin-accent',
            'value' => '#ec4899',
            'description' => 'Color acento admin',
        ],

        // Funcionalidades
        [
            'key' => 'maintenance_mode',
            'value' => '0',
            'description' => 'Modo mantenimiento (0=no, 1=sí)',
        ],
        [
            'key' => 'maintenance_message',
            'value' => 'Estamos en mantenimiento. Vuelve pronto.',
            'description' => 'Mensaje para cuando la tienda está en mantenimiento',
        ],
        [
            'key' => 'reviews_moderation',
            'value' => '1',
            'description' => 'Moderar reseñas antes de publicar (0=no, 1=sí)',
        ],
        [
            'key' => 'orders_auto_email',
            'value' => '1',
            'description' => 'Enviar emails automáticos de pedidos (0=no, 1=sí)',
        ],
        [
            'key' => 'wishlist_enabled',
            'value' => '1',
            'description' => 'Habilitar lista de deseos (0=no, 1=sí)',
        ],
    ];

    public function up(): void
    {
        foreach (self::SETTINGS as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        // We only delete the keys we explicitly added, except the ones that were there initially
        $initialKeys = ['--color-client-page', '--color-client-primary', '--color-client-login-bg', '--color-client-surface', '--color-client-surface-alt', '--color-client-border', '--color-client-text', '--color-client-muted', '--color-client-header-bg', '--color-client-footer-bg', '--color-client-card', '--color-client-card-border'];
        
        $keysToDelete = array_filter(
            array_column(self::SETTINGS, 'key'),
            fn($key) => !in_array($key, $initialKeys)
        );

        DB::table('settings')->whereIn('key', $keysToDelete)->delete();
    }
};
