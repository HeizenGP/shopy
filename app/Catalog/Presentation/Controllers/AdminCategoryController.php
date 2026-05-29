<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Infrastructure\Models\CategoryModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        return view('catalog.admin.categories.index', [
            'categories' => CategoryModel::query()->with('parent')->orderBy('sort_order')->orderBy('name')->paginate(15),
            'parents' => CategoryModel::query()->whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CategoryModel::query()->create($this->validated($request));

        return back()->with('status', 'Categoría creada.');
    }

    public function update(Request $request, int $category): RedirectResponse
    {
        CategoryModel::query()->findOrFail($category)->update($this->validated($request, $category));

        return back()->with('status', 'Categoría actualizada.');
    }

    public function destroy(int $category): RedirectResponse
    {
        CategoryModel::query()->findOrFail($category)->delete();

        return back()->with('status', 'Categoría eliminada.');
    }

    private function validated(Request $request, ?int $categoryId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:categories,slug'.($categoryId ? ','.$categoryId : '')],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
