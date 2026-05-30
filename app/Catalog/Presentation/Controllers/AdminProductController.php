<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Application\UseCases\CreateProductUseCase;
use App\Catalog\Application\UseCases\DeleteProductUseCase;
use App\Catalog\Application\UseCases\ListProductsUseCase;
use App\Catalog\Application\UseCases\ShowProductUseCase;
use App\Catalog\Application\UseCases\UpdateProductUseCase;
use App\Catalog\Domain\ValueObjects\ProductStatus;
use App\Catalog\Infrastructure\Models\BrandModel;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
use App\Catalog\Presentation\Requests\StoreProductRequest;
use App\Catalog\Presentation\Requests\UpdateProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(Request $request, ListProductsUseCase $products): View
    {
        $filters = $request->only(['search', 'status']);
        $filters['brand_id'] = $request->filled('brand_id') ? $request->integer('brand_id') : null;
        $filters['category_id'] = $request->filled('category_id') ? $request->integer('category_id') : null;

        return view('catalog.admin.products.index', [
            'products' => $products->forAdmin(filters: $filters),
            'brands' => BrandModel::query()->orderBy('name')->get(),
            'categories' => CategoryModel::query()->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('catalog.admin.products.create', $this->formData());
    }

    public function store(StoreProductRequest $request, CreateProductUseCase $createProduct): RedirectResponse
    {
        $product = $createProduct->execute($request->toData());
        $this->storeImage($request, $product);

        return redirect()
            ->route('admin.catalog.products.edit', $product)
            ->with('status', 'Producto creado.');
    }

    public function edit(int $product, ShowProductUseCase $showProduct): View
    {
        return view('catalog.admin.products.edit', [
            ...$this->formData(),
            'product' => $showProduct->byId($product) ?? abort(404),
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        int $product,
        UpdateProductUseCase $updateProduct
    ): RedirectResponse {
        $updatedProduct = $updateProduct->execute($product, $request->toData());
        $this->storeImage($request, $updatedProduct);

        return redirect()
            ->route('admin.catalog.products.edit', $product)
            ->with('status', 'Producto actualizado.');
    }

    public function destroy(int $product, DeleteProductUseCase $deleteProduct): RedirectResponse
    {
        $deleteProduct->execute($product);

        return redirect()
            ->route('admin.catalog.products.index')
            ->with('status', 'Producto eliminado.');
    }

    private function formData(): array
    {
        return [
            'brands' => BrandModel::query()->where('is_active', true)->orderBy('name')->get(),
            'categories' => CategoryModel::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
        ];
    }

    private function storeImage(StoreProductRequest|UpdateProductRequest $request, ProductModel $product): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $path = $request->file('image')->store('catalog/products', 'public');

        $product->images()->update(['is_main' => false]);
        $product->images()->create([
            'path' => $path,
            'alt_text' => $product->name,
            'is_main' => true,
            'sort_order' => 0,
        ]);
    }
}
