<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Infrastructure\Models\CategoryModel;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class PublicCategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = CategoryModel::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('catalog.public.categories.show', [
            'category' => $category,
            'products' => $category->products()->published()->with(['brand', 'images'])->paginate(12),
        ]);
    }
}
