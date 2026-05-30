<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Infrastructure\Models\ProductModel;
use App\Catalog\Infrastructure\Models\ProductVariantModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminProductVariantController extends Controller
{
    public function index(Request $request): View
    {
        $variants = ProductVariantModel::query()
            ->with('product')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('product', fn ($productQuery) => $productQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('product_id'), fn ($query) => $query->where('product_id', $request->integer('product_id')))
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('catalog.admin.variants.index', [
            'variants' => $variants,
            'products' => ProductModel::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('catalog.admin.variants.create', [
            'products' => ProductModel::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:180'],
            'sku' => ['required', 'string', 'max:120', 'unique:product_variants,sku'],
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_default'] = $request->boolean('is_default');

        ProductVariantModel::query()->create($data);
        ProductModel::query()->whereKey($data['product_id'])->update(['has_variants' => true]);

        return redirect()->route('admin.catalog.variants.index')->with('status', 'Variante creada.');
    }

    public function destroy(int $variant): RedirectResponse
    {
        ProductVariantModel::query()->findOrFail($variant)->delete();

        return back()->with('status', 'Variante eliminada.');
    }
}
