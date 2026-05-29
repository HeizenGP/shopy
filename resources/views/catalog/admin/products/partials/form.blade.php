@php
    $selectedCategories = old('category_ids', $product?->categories->pluck('id')->all() ?? []);
    $variants = old('variants', $product?->variants->map(fn ($variant) => [
        'name' => $variant->name,
        'sku' => $variant->sku,
        'regular_price' => $variant->regular_price,
        'sale_price' => $variant->sale_price,
    ])->all() ?? [['name' => '', 'sku' => '', 'regular_price' => '', 'sale_price' => '']]);
@endphp

<!-- Main Form Cards Column -->
<div class="form-card-container">
    
    <!-- Section 1: Principal Info -->
    <div class="form-section-card">
        <div class="form-section-header">
            <div class="form-section-header-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3>Información Principal</h3>
                <p>Datos comerciales básicos e identificadores visibles en el catálogo público.</p>
            </div>
        </div>

        <div class="form-layout-grid">
            <div class="form-field-group form-col-span-2">
                <label for="p_name">Nombre del Producto</label>
                <input type="text" id="p_name" name="name" value="{{ old('name', $product?->name) }}" placeholder="Ej: Zapatillas Deportivas Pegasus" required>
            </div>

            <div class="form-field-group">
                <label for="p_slug">Slug URL (Opcional)</label>
                <input type="text" id="p_slug" name="slug" value="{{ old('slug', $product?->slug) }}" placeholder="zapatillas-deportivas-pegasus">
            </div>

            <div class="form-field-group">
                <label for="p_sku">Código SKU de Referencia</label>
                <input type="text" id="p_sku" name="sku" value="{{ old('sku', $product?->sku) }}" placeholder="ZAP-PEG-001">
            </div>

            <div class="form-field-group">
                <label for="p_brand">Marca</label>
                <select id="p_brand" name="brand_id">
                    <option value="">Sin marca asociada</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field-group">
                <label for="p_main_category">Categoría Principal</label>
                <select id="p_main_category" name="main_category_id">
                    <option value="">Sin categoría principal</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('main_category_id', $product?->main_category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field-group">
                <label for="p_price">Precio Regular ($)</label>
                <input type="number" step="0.01" min="0" id="p_price" name="regular_price" value="{{ old('regular_price', $product?->regular_price ?? 0) }}" placeholder="0.00" required>
            </div>

            <div class="form-field-group">
                <label for="p_sale">Precio de Oferta ($)</label>
                <input type="number" step="0.01" min="0" id="p_sale" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}" placeholder="0.00">
            </div>

            <div class="form-field-group form-col-span-2">
                <label for="p_short_desc">Descripción Corta</label>
                <textarea id="p_short_desc" name="short_description" rows="2" placeholder="Resumen conciso del producto para tarjetas de lista...">{{ old('short_description', $product?->short_description) }}</textarea>
            </div>

            <div class="form-field-group form-col-span-2">
                <label for="p_desc">Descripción Completa</label>
                <textarea id="p_desc" name="description" rows="5" placeholder="Detalle extendido, características, materiales, especificaciones técnicas...">{{ old('description', $product?->description) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 2: Organization & Categories -->
    <div class="form-section-card">
        <div class="form-section-header">
            <div class="form-section-header-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h3>Organización del Producto</h3>
                <p>Configura las etiquetas de destaque y las categorías cruzadas donde se mostrará.</p>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Simple Switchers -->
            <div class="form-checkboxes-block" style="flex-direction: row; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.25rem;">
                <div class="form-checkbox-row">
                    <input type="checkbox" id="p_is_featured" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured))>
                    <label for="p_is_featured">Destacar este producto (Aparecerá en el inicio)</label>
                </div>
                <div class="form-checkbox-row">
                    <input type="checkbox" id="p_has_variants" name="has_variants" value="1" @checked(old('has_variants', $product?->has_variants))>
                    <label for="p_has_variants">Habilitar variantes múltiples</label>
                </div>
            </div>

            <!-- Categories Checklist -->
            <div class="form-field-group">
                <label>Categorías de Clasificación</label>
                <span class="theme-label-sm" style="margin-bottom: 0.25rem; display: block;">Selecciona una o más categorías secundarias:</span>
                <div class="categories-checklist-grid">
                    @foreach ($categories as $category)
                        <div class="form-checkbox-row">
                            <input type="checkbox" id="cat_{{ $category->id }}" name="category_ids[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))>
                            <label for="cat_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Basic Variants -->
    <div class="form-section-card">
        <div class="form-section-header">
            <div class="form-section-header-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h3>Variantes de Producto</h3>
                <p>Define SKU, nombres e importes para combinaciones de atributos (ej: Tallas, Colores).</p>
            </div>
        </div>

        <div>
            <!-- Grid Header Labels -->
            <div class="variants-header-labels">
                <span>Nombre de Variante</span>
                <span>Código SKU</span>
                <span>Precio Reg. ($)</span>
                <span>Precio Ofer. ($)</span>
            </div>

            <!-- List of input rows -->
            @for ($index = 0; $index < max(3, count($variants)); $index++)
                @php($variant = $variants[$index] ?? ['name' => '', 'sku' => '', 'regular_price' => '', 'sale_price' => ''])
                <div class="variant-input-row-card">
                    <div>
                        <input type="text" name="variants[{{ $index }}][name]" placeholder="Ej: Talla M / Color Azul" value="{{ $variant['name'] ?? '' }}">
                    </div>
                    <div>
                        <input type="text" name="variants[{{ $index }}][sku]" placeholder="Ej: SKU-M-BLU" value="{{ $variant['sku'] ?? '' }}">
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0" name="variants[{{ $index }}][regular_price]" placeholder="0.00" value="{{ $variant['regular_price'] ?? '' }}">
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0" name="variants[{{ $index }}][sale_price]" placeholder="0.00" value="{{ $variant['sale_price'] ?? '' }}">
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Floating Action Sidebar Column -->
<aside class="publish-card-actions">
    <div class="form-field-group" style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <label for="p_status">Estado de Publicación</label>
        <select id="p_status" name="status" style="margin-top: 0.5rem;">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(old('status', $product?->status?->value ?? 'draft') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    <!-- Submit buttons block -->
    <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Guardar Producto
        </button>
        <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary" style="width: 100%;">
            Cancelar
        </a>
    </div>

    <!-- Form Checklist Widget -->
    <div class="checklist-card-items" style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
        <h4 style="font-size: 0.8rem; font-weight: 700; color: var(--text-main); text-transform: uppercase; margin: 0 0 0.5rem 0;">Requisitos del Catálogo</h4>
        
        <div class="checklist-item">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Identificador y Nombre
        </div>
        <div class="checklist-item">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Precio de venta
        </div>
        <div class="checklist-item">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Clasificación de categoría
        </div>
    </div>
</aside>
