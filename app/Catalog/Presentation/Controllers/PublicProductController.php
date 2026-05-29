<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Application\UseCases\ListProductsUseCase;
use App\Catalog\Application\UseCases\ShowProductUseCase;
use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Models\CategoryModel;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class PublicProductController extends Controller
{
    public function home(ProductRepositoryInterface $products): View
    {
        return view('catalog.public.products.home', [
            'featuredProducts' => $products->featured(),
            'categories' => CategoryModel::query()->where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get(),
        ]);
    }

    public function index(ListProductsUseCase $products): View
    {
        return view('catalog.public.products.index', [
            'products' => $products->publicCatalog(),
        ]);
    }

    public function show(string $slug, ShowProductUseCase $products): View
    {
        return view('catalog.public.products.show', [
            'product' => $products->publishedBySlug($slug) ?? abort(404),
        ]);
    }
}
