<x-layouts.admin title="Nueva Categoría">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Crear Categoría</h1>
            <p>Registra una categoría del catálogo y su imagen principal.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <form class="admin-split-form-grid" method="POST" action="{{ route('admin.catalog.categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-card-container">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3>Información de Categoría</h3>
                        <p>Define nombre, jerarquía y orden de aparición.</p>
                    </div>
                </div>

                <div class="form-layout-grid">
                    <div class="form-field-group">
                        <label for="category_name">Nombre</label>
                        <input type="text" id="category_name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-field-group">
                        <label for="category_slug">Slug</label>
                        <input type="text" id="category_slug" name="slug" value="{{ old('slug') }}" placeholder="opcional">
                    </div>
                    <div class="form-field-group">
                        <label for="category_parent">Categoría Padre</label>
                        <select id="category_parent" name="parent_id">
                            <option value="">Ninguna</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label for="category_order">Orden</label>
                        <input type="number" id="category_order" name="sort_order" min="0" value="{{ old('sort_order', 0) }}">
                    </div>
                    <div class="form-field-group form-col-span-2">
                        <label for="category_description">Descripción</label>
                        <textarea id="category_description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-field-group form-col-span-2">
                        <label for="category_image">Imagen</label>
                        <input type="file" id="category_image" name="image" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <aside class="publish-card-actions">
            <div class="form-checkbox-row">
                <input type="checkbox" id="category_is_active" name="is_active" value="1" @checked(old('is_active', true))>
                <label for="category_is_active">Categoría activa</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Categoría</button>
        </aside>
    </form>
</x-layouts.admin>
