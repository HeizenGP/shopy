<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Infrastructure\Models\CategoryModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $request = request();

        return view('catalog.admin.categories.index', [
            'categories' => CategoryModel::query()
                ->with(['parent.parent'])
                ->when($request->filled('search'), function ($query) use ($request): void {
                    $search = $request->string('search')->toString();
                    $query->where(function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->when($request->filled('parent_id'), fn ($query) => $query->where('parent_id', $request->integer('parent_id')))
                ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
            'parents' => $this->parentOptions(),
        ]);
    }

    public function create(): View
    {
        return view('catalog.admin.categories.create', [
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CategoryModel::query()->create($this->validated($request));

        return redirect()->route('admin.catalog.categories.index')->with('status', 'Categoría creada.');
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
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('catalog/categories', 'public');
        }

        $this->validateHierarchy($data['parent_id'] ?? null, $categoryId);

        return $data;
    }

    private function parentOptions()
    {
        return CategoryModel::query()
            ->with('parent')
            ->where(function ($query): void {
                $query->whereNull('parent_id')
                    ->orWhereHas('parent', fn ($parentQuery) => $parentQuery->whereNull('parent_id'));
            })
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function validateHierarchy(?int $parentId, ?int $categoryId = null): void
    {
        if (! $parentId) {
            return;
        }

        if ($categoryId && $parentId === $categoryId) {
            throw ValidationException::withMessages([
                'parent_id' => 'Una categoría no puede ser padre de sí misma.',
            ]);
        }

        $parent = CategoryModel::query()->with('parent.parent')->findOrFail($parentId);

        if ($parent->level() >= 2) {
            throw ValidationException::withMessages([
                'parent_id' => 'Solo se permiten 3 niveles: categoría padre, subcategoría y sub-subcategoría.',
            ]);
        }

        if ($categoryId && $this->isDescendantOf($parent, $categoryId)) {
            throw ValidationException::withMessages([
                'parent_id' => 'No puedes mover una categoría debajo de una de sus subcategorías.',
            ]);
        }

        if ($categoryId) {
            $category = CategoryModel::query()->with('children.children')->findOrFail($categoryId);
            $newLevel = $parent->level() + 1;
            $maxChildDepth = $this->maxChildDepth($category);

            if ($newLevel + $maxChildDepth > 2) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Ese movimiento superaría el límite de 3 niveles.',
                ]);
            }
        }
    }

    private function isDescendantOf(CategoryModel $category, int $ancestorId): bool
    {
        $parent = $category->parent;

        while ($parent) {
            if ($parent->id === $ancestorId) {
                return true;
            }

            $parent = $parent->parent;
        }

        return false;
    }

    private function maxChildDepth(CategoryModel $category): int
    {
        if ($category->children->isEmpty()) {
            return 0;
        }

        return 1 + $category->children->max(fn (CategoryModel $child) => $this->maxChildDepth($child));
    }
}
