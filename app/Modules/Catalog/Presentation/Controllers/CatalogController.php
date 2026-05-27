<?php

namespace App\Modules\Catalog\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Application\UseCases\ListCatalogUseCase;
use App\Modules\Catalog\Application\UseCases\GetProductDetailsUseCase;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(
        private readonly ListCatalogUseCase $listCatalogUseCase,
        private readonly GetProductDetailsUseCase $getProductDetailsUseCase
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'min_price', 'max_price']);
        $products = $this->listCatalogUseCase->execute($filters);
        
        // Extract unique categories for filter sidebar
        $allProducts = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::where('is_active', true)->get();
        $categories = $allProducts->pluck('category')->filter()->unique()->values()->toArray();

        return view('catalog.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        try {
            $product = $this->getProductDetailsUseCase->execute($slug);
            
            // Get reviews for this product
            $reviews = \App\Modules\Reviews\Infrastructure\Database\Models\ReviewEloquent::where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate average rating
            $averageRating = $reviews->avg('rating') ?: 0;

            // Get inventory levels for product and its variants
            $inventoryLevels = \App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent::where('product_id', $product->id)
                ->get()
                ->keyBy(fn($item) => $item->product_variant_id ?: 'base');

            return view('catalog.show', compact('product', 'reviews', 'averageRating', 'inventoryLevels'));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
