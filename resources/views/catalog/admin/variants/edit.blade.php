<x-layouts.admin title="Editar {{ $variant->name }}">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Editar Variante</h1>
            <p>Actualiza la variante <strong>{{ $variant->name }}</strong> y su producto asociado.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.variants.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <form class="admin-split-form-grid" method="POST" action="{{ route('admin.catalog.variants.update', $variant) }}">
        @csrf
        @method('PUT')
        <div class="form-card-container">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3>Información de Variante</h3>
                        <p>Define producto base, SKU y precios propios de la variante.</p>
                    </div>
                </div>

                <div class="form-layout-grid">
                    <div class="form-field-group form-col-span-2">
                        <label for="variant_product">Producto</label>
                        <select id="variant_product" name="product_id" required>
                            <option value="">Seleccionar producto</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id', $variant->product_id) == $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label for="variant_name">Nombre</label>
                        <input type="text" id="variant_name" name="name" value="{{ old('name', $variant->name) }}" placeholder="Color Azul / Talla M" required>
                    </div>
                    <div class="form-field-group">
                        <label for="variant_sku">SKU</label>
                        <input type="text" id="variant_sku" name="sku" value="{{ old('sku', $variant->sku) }}" placeholder="SKU-AZUL-M" required>
                    </div>
                    <div class="form-field-group">
                        <label for="variant_regular_price">Precio Regular</label>
                        <input type="number" step="0.01" min="0" id="variant_regular_price" name="regular_price" value="{{ old('regular_price', $variant->regular_price) }}" placeholder="0.00">
                    </div>
                    <div class="form-field-group">
                        <label for="variant_sale_price">Precio Oferta</label>
                        <input type="number" step="0.01" min="0" id="variant_sale_price" name="sale_price" value="{{ old('sale_price', $variant->sale_price) }}" placeholder="0.00">
                    </div>
                    <div class="form-field-group">
                        <label for="variant_weight">Peso</label>
                        <input type="number" step="0.01" min="0" id="variant_weight" name="weight" value="{{ old('weight', $variant->weight) }}" placeholder="0.00">
                    </div>
                </div>
            </div>
        </div>

        <aside class="publish-card-actions">
            <div class="form-checkbox-row">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="variant_is_active" name="is_active" value="1" @checked(old('is_active', $variant->is_active))>
                <label for="variant_is_active">Variante activa</label>
            </div>
            <div class="form-checkbox-row">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" id="variant_is_default" name="is_default" value="1" @checked(old('is_default', $variant->is_default))>
                <label for="variant_is_default">Variante principal</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Variante</button>
        </aside>
    </form>
</x-layouts.admin>
