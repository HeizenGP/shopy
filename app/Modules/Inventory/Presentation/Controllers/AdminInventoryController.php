<?php

namespace App\Modules\Inventory\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Application\UseCases\GetStockAlertsUseCase;
use App\Modules\Inventory\Domain\Entities\Inventory;
use App\Modules\Inventory\Domain\Repositories\InventoryRepositoryInterface;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function __construct(
        private readonly GetStockAlertsUseCase $getStockAlertsUseCase,
        private readonly InventoryRepositoryInterface $inventoryRepository
    ) {}

    public function index()
    {
        $alerts = $this->getStockAlertsUseCase->execute();
        
        $inventories = \App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent::all();

        $products = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::with('variants.options')->get()->keyBy('id');

        return view('admin.inventory', compact('alerts', 'inventories', 'products'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $eloquent = \App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent::findOrFail($id);
        
        $inventory = new Inventory(
            id: $eloquent->id,
            productId: $eloquent->product_id,
            productVariantId: $eloquent->product_variant_id,
            stock: (int) $data['stock'],
            lowStockThreshold: (int) $data['low_stock_threshold']
        );

        $this->inventoryRepository->save($inventory);

        if ($inventory->stock > $inventory->lowStockThreshold) {
            $this->inventoryRepository->resolveAlertsForProduct($inventory->productId, $inventory->productVariantId);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Inventario actualizado correctamente.');
    }
}
