<?php

namespace App\Modules\Admin\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;
use App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\StockAlertEloquent;
use App\Modules\Orders\Infrastructure\Database\Models\OrderEloquent;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
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
        return view('admin.super.settings');
    }
}
