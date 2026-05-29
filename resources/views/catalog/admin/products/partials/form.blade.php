@php
    $selectedCategories = old('category_ids', $product?->categories->pluck('id')->all() ?? []);
    $variants = old('variants', $product?->variants->map(fn ($variant) => [
        'name' => $variant->name,
        'sku' => $variant->sku,
        'regular_price' => $variant->regular_price,
        'sale_price' => $variant->sale_price,
    ])->all() ?? [['name' => '', 'sku' => '', 'regular_price' => '', 'sale_price' => '']]);
@endphp

<label>Nombre
    <input name="name" value="{{ old('name', $product?->name) }}" required>
</label>
<label>Slug
    <input name="slug" value="{{ old('slug', $product?->slug) }}">
</label>
<label>SKU
    <input name="sku" value="{{ old('sku', $product?->sku) }}">
</label>
<label>Marca
    <select name="brand_id">
        <option value="">Sin marca</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
        @endforeach
    </select>
</label>
<label>Categoria principal
    <select name="main_category_id">
        <option value="">Sin categoria</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('main_category_id', $product?->main_category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</label>
<label>Estado
    <select name="status">
        @foreach ($statuses as $status)
            <option value="{{ $status->value }}" @selected(old('status', $product?->status?->value ?? 'draft') === $status->value)>{{ $status->label() }}</option>
        @endforeach
    </select>
</label>
<label>Precio regular
    <input type="number" step="0.01" min="0" name="regular_price" value="{{ old('regular_price', $product?->regular_price ?? 0) }}" required>
</label>
<label>Precio oferta
    <input type="number" step="0.01" min="0" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}">
</label>
<label class="span-2">Descripcion corta
    <textarea name="short_description" rows="2">{{ old('short_description', $product?->short_description) }}</textarea>
</label>
<label class="span-2">Descripcion
    <textarea name="description" rows="5">{{ old('description', $product?->description) }}</textarea>
</label>
<fieldset class="span-2 checks">
    <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured))> Destacado</label>
    <label><input type="checkbox" name="has_variants" value="1" @checked(old('has_variants', $product?->has_variants))> Tiene variantes</label>
</fieldset>
<fieldset class="span-2 checks">
    <legend>Categorias</legend>
    @foreach ($categories as $category)
        <label><input type="checkbox" name="category_ids[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))> {{ $category->name }}</label>
    @endforeach
</fieldset>
<fieldset class="span-2">
    <legend>Variantes basicas</legend>
    @for ($index = 0; $index < max(3, count($variants)); $index++)
        @php($variant = $variants[$index] ?? ['name' => '', 'sku' => '', 'regular_price' => '', 'sale_price' => ''])
        <div class="variant-row">
            <input name="variants[{{ $index }}][name]" placeholder="Nombre" value="{{ $variant['name'] ?? '' }}">
            <input name="variants[{{ $index }}][sku]" placeholder="SKU" value="{{ $variant['sku'] ?? '' }}">
            <input name="variants[{{ $index }}][regular_price]" type="number" step="0.01" min="0" placeholder="Precio" value="{{ $variant['regular_price'] ?? '' }}">
            <input name="variants[{{ $index }}][sale_price]" type="number" step="0.01" min="0" placeholder="Oferta" value="{{ $variant['sale_price'] ?? '' }}">
        </div>
    @endfor
</fieldset>
<div class="form-actions span-2">
    <a href="{{ route('admin.catalog.products.index') }}">Cancelar</a>
    <button class="button" type="submit">Guardar</button>
</div>
