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
        return view('catalog.admin.brands.index', [
            'brands' => BrandModel::query()->orderBy('name')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BrandModel::query()->create($this->validated($request));

        return back()->with('status', 'Marca creada.');
    }

    public function update(Request $request, int $brand): RedirectResponse
    {
        BrandModel::query()->findOrFail($brand)->update($this->validated($request, $brand));

        return back()->with('status', 'Marca actualizada.');
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
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
