<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Domain\Repositories\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Models\CategoryModel;
use App\Catalog\Infrastructure\Models\ProductModel;
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

    public function index(): View
    {
        $query = ProductModel::query()
            ->published()
            ->with(['brand', 'mainCategory', 'images']);

        // Search Filter (checks title, sku, short description, and brand name)
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($qb) use ($search) {
                      $qb->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter (supports single category slug or array of slugs)
        if ($categories = request('category')) {
            $query->whereHas('categories', function ($q) use ($categories) {
                if (is_array($categories)) {
                    $q->whereIn('slug', $categories);
                } else {
                    $q->where('slug', $categories);
                }
            });
        }

        // Brand Filter (supports single brand slug or array of slugs)
        if ($brands = request('brand')) {
            $query->whereHas('brand', function ($q) use ($brands) {
                if (is_array($brands)) {
                    $q->whereIn('slug', $brands);
                } else {
                    $q->where('slug', $brands);
                }
            });
        }

        // Price Filter
        if ($maxPrice = request('max_price')) {
            $query->where(function ($q) use ($maxPrice) {
                $q->where(function ($sq) use ($maxPrice) {
                    $sq->whereNotNull('sale_price')->where('sale_price', '<=', $maxPrice);
                })->orWhere(function ($sq) use ($maxPrice) {
                    $sq->whereNull('sale_price')->where('regular_price', '<=', $maxPrice);
                });
            });
        }

        // Feature Filter (supports featured URL query)
        if (request()->has('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sort = request('sort', 'latest');
        if ($sort === 'price-asc') {
            $query->orderByRaw('COALESCE(sale_price, regular_price) ASC');
        } elseif ($sort === 'price-desc') {
            $query->orderByRaw('COALESCE(sale_price, regular_price) DESC');
        } elseif ($sort === 'name-asc') {
            $query->orderBy('name', 'ASC');
        } else {
            $query->latest('published_at');
        }

        // Paginate results with query parameters appended
        $products = $query->paginate(12)->withQueryString();

        return view('catalog.public.products.index', [
            'products' => $products,
        ]);
    }

    public function show(string $slug): View
    {
        $product = ProductModel::query()
            ->published()
            ->with(['brand', 'mainCategory', 'categories', 'variants', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('catalog.public.products.show', [
            'product' => $product,
        ]);
    }
}
