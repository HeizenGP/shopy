<?php

namespace App\Modules\Admin\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\StockAlertEloquent;
use App\Modules\Orders\Infrastructure\Database\Models\OrderEloquent;
use App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SuperAdminController extends Controller
{
    private const SETTINGS_GROUPS = [
        'general' => [
            'title' => 'Información de la Tienda',
            'icon' => 'store',
            'fields' => [
                'shop_name' => [
                    'key' => 'shop_name',
                    'label' => 'Nombre de la Tienda',
                    'type' => 'text',
                    'default' => 'ShopCMS',
                    'rules' => 'required|string|max:100',
                    'description' => 'El nombre público de tu tienda virtual.',
                ],
                'shop_email' => [
                    'key' => 'shop_email',
                    'label' => 'Email de Contacto',
                    'type' => 'email',
                    'default' => 'info@shop.com',
                    'rules' => 'required|email',
                    'description' => 'El email principal de contacto de la tienda.',
                ],
                'shop_phone' => [
                    'key' => 'shop_phone',
                    'label' => 'Teléfono de Contacto',
                    'type' => 'text',
                    'default' => '+1 234 567 890',
                    'rules' => 'nullable|string|max:30',
                    'description' => 'El teléfono de soporte para tus clientes.',
                ],
                'shop_description' => [
                    'key' => 'shop_description',
                    'label' => 'Descripción de la tienda (SEO)',
                    'type' => 'textarea',
                    'default' => 'Tu tienda virtual premium',
                    'rules' => 'nullable|string|max:500',
                    'description' => 'Meta descripción utilizada por los buscadores (Google, etc.).',
                ],
            ]
        ],
        'branding' => [
            'title' => 'Diseño & Colores',
            'icon' => 'palette',
            'fields' => [
                'logo_url' => [
                    'key' => 'logo_url',
                    'label' => 'URL del Logo',
                    'type' => 'text',
                    'default' => '/images/logo.png',
                    'rules' => 'required|string',
                    'description' => 'Ruta o URL del logo oficial de la tienda.',
                ],
                'favicon_url' => [
                    'key' => 'favicon_url',
                    'label' => 'URL del Favicon',
                    'type' => 'text',
                    'default' => '/favicon.ico',
                    'rules' => 'required|string',
                    'description' => 'Ícono pequeño mostrado en la pestaña del navegador.',
                ],
                'banner_url' => [
                    'key' => 'banner_url',
                    'label' => 'URL del Banner Principal',
                    'type' => 'text',
                    'default' => '/images/banner.jpg',
                    'rules' => 'nullable|string',
                    'description' => 'Imagen promocional principal o fondo de cabecera.',
                ],
                'color_client_page' => [
                    'key' => '--color-client-page',
                    'label' => 'Color de Fondo (Cliente)',
                    'type' => 'color',
                    'default' => '#f8fafc',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Fondo de la web pública de la tienda.',
                ],
                'color_client_primary' => [
                    'key' => '--color-client-primary',
                    'label' => 'Color Primario (Cliente)',
                    'type' => 'color',
                    'default' => '#4f46e5',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Color para botones principales, enlaces y acentos.',
                ],
                'color_client_login_bg' => [
                    'key' => '--color-client-login-bg',
                    'label' => 'Color de Fondo Login (Cliente)',
                    'type' => 'color',
                    'default' => '#eef2ff',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Fondo para el formulario de login público.',
                ],
                'color_admin_sidebar' => [
                    'key' => '--color-admin-sidebar',
                    'label' => 'Color Sidebar (Admin)',
                    'type' => 'color',
                    'default' => '#202123',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Fondo del menú lateral del panel administrativo.',
                ],
                'color_admin_primary' => [
                    'key' => '--color-admin-primary',
                    'label' => 'Color Primario (Admin)',
                    'type' => 'color',
                    'default' => '#4f46e5',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Color de botones principales en el admin.',
                ],
                'color_admin_accent' => [
                    'key' => '--color-admin-accent',
                    'label' => 'Color Acento (Admin)',
                    'type' => 'color',
                    'default' => '#ec4899',
                    'rules' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                    'description' => 'Color de acento en el admin.',
                ],
            ]
        ],
        'store' => [
            'title' => 'Moneda & Región',
            'icon' => 'coin',
            'fields' => [
                'shop_currency' => [
                    'key' => 'shop_currency',
                    'label' => 'Moneda (Código ISO)',
                    'type' => 'text',
                    'default' => 'USD',
                    'rules' => 'required|string|max:3',
                    'description' => 'Código de tres letras de la moneda (USD, EUR, PEN, etc.).',
                ],
                'shop_currency_symbol' => [
                    'key' => 'shop_currency_symbol',
                    'label' => 'Símbolo de Moneda',
                    'type' => 'text',
                    'default' => '$',
                    'rules' => 'required|string|max:5',
                    'description' => 'Símbolo a mostrar en los precios ($ , € , S/., etc.).',
                ],
                'shop_tax_rate' => [
                    'key' => 'shop_tax_rate',
                    'label' => 'Tasa de Impuestos (%)',
                    'type' => 'number',
                    'default' => '16',
                    'rules' => 'required|numeric|min:0|max:100',
                    'description' => 'Porcentaje de IVA o impuesto aplicable a las órdenes.',
                ],
                'shipping_free_threshold' => [
                    'key' => 'shipping_free_threshold',
                    'label' => 'Envío Gratis a partir de',
                    'type' => 'number',
                    'default' => '100',
                    'rules' => 'required|numeric|min:0',
                    'description' => 'Monto mínimo de compra para que el envío sea gratuito.',
                ],
                'timezone' => [
                    'key' => 'timezone',
                    'label' => 'Zona Horaria',
                    'type' => 'text',
                    'default' => 'America/New_York',
                    'rules' => 'required|string',
                    'description' => 'Timezone oficial para reportes y timestamps del sistema.',
                ],
            ]
        ],
        'features' => [
            'title' => 'Funcionalidades',
            'icon' => 'settings-toggle',
            'fields' => [
                'maintenance_mode' => [
                    'key' => 'maintenance_mode',
                    'label' => 'Modo Mantenimiento',
                    'type' => 'boolean',
                    'default' => '0',
                    'rules' => 'required|in:0,1',
                    'description' => 'Bloquea el sitio público mostrando una pantalla temporal de mantenimiento.',
                ],
                'maintenance_message' => [
                    'key' => 'maintenance_message',
                    'label' => 'Mensaje de Mantenimiento',
                    'type' => 'text',
                    'default' => 'Estamos en mantenimiento. Vuelve pronto.',
                    'rules' => 'nullable|string|max:255',
                    'description' => 'Mensaje que verán los clientes si el modo mantenimiento está activo.',
                ],
                'reviews_moderation' => [
                    'key' => 'reviews_moderation',
                    'label' => 'Moderar Reseñas',
                    'type' => 'boolean',
                    'default' => '1',
                    'rules' => 'required|in:0,1',
                    'description' => 'Si está activo, las reseñas de productos requerirán aprobación manual de un administrador.',
                ],
                'orders_auto_email' => [
                    'key' => 'orders_auto_email',
                    'label' => 'Emails Automáticos de Pedidos',
                    'type' => 'boolean',
                    'default' => '1',
                    'rules' => 'required|in:0,1',
                    'description' => 'Envía correos electrónicos automatizados al cliente en cada actualización de su orden.',
                ],
                'wishlist_enabled' => [
                    'key' => 'wishlist_enabled',
                    'label' => 'Habilitar Wishlist',
                    'type' => 'boolean',
                    'default' => '1',
                    'rules' => 'required|in:0,1',
                    'description' => 'Permite a los usuarios guardar productos en su lista de deseos.',
                ],
            ]
        ]
    ];

    public function dashboard()
    {
        $stats = [
            'users' => UserEloquent::count(),
            'orders' => OrderEloquent::count(),
            'revenue' => OrderEloquent::where('status', 'completed')->sum('total'),
            'products' => ProductEloquent::count(),
            'lowStock' => InventoryEloquent::whereColumn('stock', '<=', 'low_stock_threshold')->count(),
            'alerts' => StockAlertEloquent::where('is_resolved', false)->count(),
        ];

        $recentOrders = OrderEloquent::latest()->limit(5)->get();
        $topCategories = ProductEloquent::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return view('admin.super.dashboard', compact('stats', 'recentOrders', 'topCategories'));
    }

    public function users()
    {
        $users = UserEloquent::orderBy('role')->orderBy('name')->get();

        return view('admin.super.users', compact('users'));
    }

    public function updateUserRole(Request $request, int $id)
    {
        $data = $request->validate([
            'role' => 'required|in:super_admin,sales_admin,customer',
        ]);

        UserEloquent::findOrFail($id)->update(['role' => $data['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function reports()
    {
        $stats = [
            'pendingOrders' => OrderEloquent::where('status', 'pending')->count(),
            'processingOrders' => OrderEloquent::where('status', 'processing')->count(),
            'completedOrders' => OrderEloquent::where('status', 'completed')->count(),
            'cancelledOrders' => OrderEloquent::where('status', 'cancelled')->count(),
            'totalRevenue' => OrderEloquent::where('status', 'completed')->sum('total'),
            'activeProducts' => ProductEloquent::where('is_active', true)->count(),
        ];

        return view('admin.super.reports', compact('stats'));
    }

    public function plugins()
    {
        return view('admin.super.plugins');
    }

    public function settings()
    {
        $stored = collect();
        if (Schema::hasTable('settings')) {
            $stored = SettingEloquent::pluck('value', 'key');
        }

        $groups = [];
        foreach (self::SETTINGS_GROUPS as $groupKey => $groupData) {
            $fields = [];
            foreach ($groupData['fields'] as $inputName => $definition) {
                $fields[$inputName] = [
                    ...$definition,
                    'value' => $stored[$definition['key']] ?? $definition['default'],
                ];
            }
            $groups[$groupKey] = [
                'title' => $groupData['title'],
                'icon' => $groupData['icon'],
                'fields' => $fields,
            ];
        }

        return view('admin.super.settings', compact('groups'));
    }

    public function updateSettings(Request $request)
    {
        if (!Schema::hasTable('settings')) {
            return redirect()->route('admin.settings')->with('error', 'La tabla de configuraciones no está disponible.');
        }

        $rules = [];
        foreach (self::SETTINGS_GROUPS as $groupData) {
            foreach ($groupData['fields'] as $inputName => $definition) {
                $rules[$inputName] = explode('|', $definition['rules']);
            }
        }

        $data = $request->validate($rules);

        foreach (self::SETTINGS_GROUPS as $groupData) {
            foreach ($groupData['fields'] as $inputName => $definition) {
                if (array_key_exists($inputName, $data)) {
                    SettingEloquent::updateOrCreate(
                        ['key' => $definition['key']],
                        [
                            'value' => $data[$inputName],
                            'description' => $definition['description'] ?? '',
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.settings')->with('success', 'Configuración actualizada correctamente.');
    }

    public function exportSettings()
    {
        if (!Schema::hasTable('settings')) {
            return redirect()->route('admin.settings')->with('error', 'La tabla de configuraciones no está disponible.');
        }

        $settings = SettingEloquent::all(['key', 'value', 'description'])->toArray();
        $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        $filename = 'shopcms_settings_' . date('Y-m-d_His') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function importSettings(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json,txt',
        ]);

        try {
            $fileContent = file_get_contents($request->file('settings_file')->getRealPath());
            $settings = json_decode($fileContent, true);

            if (!is_array($settings)) {
                throw new \Exception('El formato del archivo JSON es inválido.');
            }

            foreach ($settings as $setting) {
                if (!isset($setting['key']) || !array_key_exists('value', $setting)) {
                    throw new \Exception('El archivo de configuración contiene campos corruptos.');
                }
            }

            foreach ($settings as $setting) {
                SettingEloquent::updateOrCreate(
                    ['key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'description' => $setting['description'] ?? '',
                    ]
                );
            }

            return redirect()->route('admin.settings')->with('success', 'Configuración importada y restaurada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings')->with('error', 'Error al importar configuración: ' . $e->getMessage());
        }
    }
}
