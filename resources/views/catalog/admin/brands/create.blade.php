<x-layouts.admin title="Nueva Marca">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Crear Marca</h1>
            <p>Registra una marca y carga su imagen o logotipo.</p>
        </div>
        <div class="page-actions-area">
            <a href="{{ route('admin.catalog.brands.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <form class="admin-split-form-grid" method="POST" action="{{ route('admin.catalog.brands.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-card-container">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3>Información de Marca</h3>
                        <p>Configura el identificador público y la imagen de marca.</p>
                    </div>
                </div>

                <div class="form-layout-grid">
                    <div class="form-field-group">
                        <label for="brand_name">Nombre</label>
                        <input type="text" id="brand_name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-field-group">
                        <label for="brand_slug">Slug</label>
                        <input type="text" id="brand_slug" name="slug" value="{{ old('slug') }}" placeholder="opcional">
                    </div>
                    <div class="form-field-group form-col-span-2">
                        <label for="brand_description">Descripción</label>
                        <textarea id="brand_description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-field-group form-col-span-2">
                        <label for="brand_logo">Logo o Imagen</label>
                        <input type="file" id="brand_logo" name="logo" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <aside class="publish-card-actions">
            <div class="form-checkbox-row">
                <input type="checkbox" id="brand_is_active" name="is_active" value="1" @checked(old('is_active', true))>
                <label for="brand_is_active">Marca activa</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Marca</button>
        </aside>
    </form>
</x-layouts.admin>
