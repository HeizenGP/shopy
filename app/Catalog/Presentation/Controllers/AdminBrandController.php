<?php

namespace App\Catalog\Presentation\Controllers;

use App\Catalog\Infrastructure\Models\BrandModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBrandController extends Controller
{
    public function index(): View
    {
        $request = request();

        return view('catalog.admin.brands.index', [
            'brands' => BrandModel::query()
                ->when($request->filled('search'), function ($query) use ($request): void {
                    $search = $request->string('search')->toString();
                    $query->where(function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('catalog.admin.brands.create');
    }

    public function edit(int $brand): View
    {
        return view('catalog.admin.brands.edit', [
            'brand' => BrandModel::query()->findOrFail($brand),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BrandModel::query()->create($this->validated($request));

        return redirect()->route('admin.catalog.brands.index')->with('status', 'Marca creada.');
    }

    public function update(Request $request, int $brand): RedirectResponse
    {
        BrandModel::query()->findOrFail($brand)->update($this->validated($request, $brand));

        return redirect()->route('admin.catalog.brands.edit', $brand)->with('status', 'Marca actualizada.');
    }

    public function destroy(int $brand): RedirectResponse
    {
        BrandModel::query()->findOrFail($brand)->delete();

        return back()->with('status', 'Marca eliminada.');
    }

    private function validated(Request $request, ?int $brandId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:brands,slug'.($brandId ? ','.$brandId : '')],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('catalog/brands', 'public');
        }

        return $data;
    }
}
