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
    private const THEME_SETTINGS = [
        'color_client_page' => [
            'key' => '--color-client-page',
            'label' => 'Fondo general del frontend cliente',
            'description' => 'Color base para las páginas públicas.',
            'default' => '#f8fafc',
        ],
        'color_client_primary' => [
            'key' => '--color-client-primary',
            'label' => 'Color principal de botones, enlaces y textos importantes',
            'description' => 'Color de acento para acciones y enlaces.',
            'default' => '#4f46e5',
        ],
        'color_client_login_bg' => [
            'key' => '--color-client-login-bg',
            'label' => 'Fondo exclusivo de la pantalla de login del cliente',
            'description' => 'Fondo usado en el formulario de acceso.',
            'default' => '#eef2ff',
        ],
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
        $definitions = self::THEME_SETTINGS;
        $stored = collect();
        if (Schema::hasTable('settings')) {
            $stored = SettingEloquent::whereIn('key', array_column($definitions, 'key'))
                ->pluck('value', 'key');
        }

        $fields = [];
        foreach ($definitions as $input => $definition) {
            $fields[$input] = [
                ...$definition,
                'value' => $stored[$definition['key']] ?? $definition['default'],
            ];
        }

        return view('admin.super.settings', compact('fields'));
    }

    public function updateSettings(Request $request)
    {
        if (!Schema::hasTable('settings')) {
            return redirect()->route('admin.settings')->with('error', 'La tabla de configuraciones no está disponible.');
        }

        $rules = [];
        foreach (self::THEME_SETTINGS as $input => $definition) {
            $rules[$input] = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        }

        $data = $request->validate($rules);

        foreach (self::THEME_SETTINGS as $input => $definition) {
            SettingEloquent::updateOrCreate(
                ['key' => $definition['key']],
                [
                    'value' => $data[$input],
                    'description' => $definition['description'],
                ]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Configuración actualizada correctamente.');
    }
}
